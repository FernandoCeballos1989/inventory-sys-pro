<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        // --- LIMPIEZA PREVENTIVA ---
        DB::statement("DROP VIEW IF EXISTS vista_productos_criticos");
        DB::unprepared("DROP TRIGGER IF EXISTS tr_actualizar_stock_after_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS tr_stock_after_delete");

        // --- 1. TRIGGER: INSERT (MySQL vs SQLite) ---
        if ($driver === 'mysql') {
            DB::unprepared("
                CREATE TRIGGER tr_actualizar_stock_after_insert
                AFTER INSERT ON stocks
                FOR EACH ROW
                BEGIN
                    IF NEW.type = 'out' AND (SELECT current_stock FROM products WHERE id = NEW.product_id) < NEW.quantity THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Error: Stock insuficiente para realizar la salida';
                    END IF;

                    IF NEW.type = 'in' THEN
                        UPDATE products SET current_stock = current_stock + NEW.quantity WHERE id = NEW.product_id;
                    ELSEIF NEW.type = 'out' THEN
                        UPDATE products SET current_stock = current_stock - NEW.quantity WHERE id = NEW.product_id;
                    END IF;
                END
            ");
        } else {
            // Sintaxis para SQLite (Demo)
            DB::unprepared("
                CREATE TRIGGER tr_actualizar_stock_after_insert
                AFTER INSERT ON stocks
                FOR EACH ROW
                BEGIN
                    SELECT RAISE(ABORT, 'Error: Stock insuficiente para realizar la salida')
                    WHERE NEW.type = 'out' AND (SELECT current_stock FROM products WHERE id = NEW.product_id) < NEW.quantity;

                    UPDATE products SET current_stock = current_stock + NEW.quantity 
                    WHERE id = NEW.product_id AND NEW.type = 'in';

                    UPDATE products SET current_stock = current_stock - NEW.quantity 
                    WHERE id = NEW.product_id AND NEW.type = 'out';
                END
            ");
        }

        // --- 2. VISTA: REPORTE DE REPOSICIÓN ---
        // SQLite no soporta 'CREATE OR REPLACE', así que usamos DROP (ya hecho arriba) + CREATE
        DB::statement("
            CREATE VIEW vista_productos_criticos AS
            SELECT 
                sku, 
                name, 
                current_stock, 
                min_stock, 
                (min_stock - current_stock) AS unidades_a_pedir
            FROM products
            WHERE current_stock <= min_stock
        ");

        // --- 3. TRIGGER: DELETE (MySQL vs SQLite) ---
        if ($driver === 'mysql') {
            DB::unprepared("
                CREATE TRIGGER tr_stock_after_delete
                AFTER DELETE ON stocks
                FOR EACH ROW
                BEGIN
                    IF OLD.type = 'in' THEN
                        UPDATE products SET current_stock = current_stock - OLD.quantity WHERE id = OLD.product_id;
                    ELSEIF OLD.type = 'out' THEN
                        UPDATE products SET current_stock = current_stock + OLD.quantity WHERE id = OLD.product_id;
                    END IF;
                END
            ");
        } else {
            // Sintaxis para SQLite (Demo)
            DB::unprepared("
                CREATE TRIGGER tr_stock_after_delete
                AFTER DELETE ON stocks
                FOR EACH ROW
                BEGIN
                    UPDATE products SET current_stock = current_stock - OLD.quantity 
                    WHERE id = OLD.product_id AND OLD.type = 'in';

                    UPDATE products SET current_stock = current_stock + OLD.quantity 
                    WHERE id = OLD.product_id AND OLD.type = 'out';
                END
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Eliminar la vista primero (ya que depende de la tabla products)
        DB::statement("DROP VIEW IF EXISTS vista_productos_criticos");

        // 2. Eliminar los triggers
        DB::unprepared("DROP TRIGGER IF EXISTS tr_actualizar_stock_after_insert");
        DB::unprepared("DROP TRIGGER IF EXISTS tr_stock_after_delete");
    }
};
