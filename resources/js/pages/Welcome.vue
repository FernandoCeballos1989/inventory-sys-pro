<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { dashboard, login, register } from '@/routes';

const dashboardShot = new URL('../../assets/img/Screenshot_1.png', import.meta.url).href;
const flow1 = new URL('../../assets/img/flow-1.gif', import.meta.url).href;
const flow2 = new URL('../../assets/img/flow-2.gif', import.meta.url).href;
const flow3 = new URL('../../assets/img/flow-3.gif', import.meta.url).href;

const demoGifs = [flow1, flow2, flow3];
const demoCaptions = [
    'Create instances of everything you need to manage your inventory.',
    'Dark mode support.',
    'Search between your products, clients, providers.',
];
const currentGifIndex = ref(0);
const currentGifSrc = computed(() => demoGifs[currentGifIndex.value]);
const currentGifCaption = computed(() => demoCaptions[currentGifIndex.value]);

let gifRotationTimer: ReturnType<typeof setInterval> | null = null;

onMounted(() => {
    gifRotationTimer = setInterval(() => {
        currentGifIndex.value = (currentGifIndex.value + 1) % demoGifs.length;
    }, 7000);
});

onBeforeUnmount(() => {
    if (gifRotationTimer) {
        clearInterval(gifRotationTimer);
    }
});

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>

    <div class="min-h-screen bg-[#f8f8f6] text-[#171717] dark:bg-[#090909] dark:text-[#f2f2f2]">
        <div class="mx-auto flex min-h-screen max-w-6xl flex-col px-6 py-6 lg:px-8 lg:py-8">
            <header class="w-full text-sm not-has-[nav]:hidden">
                <nav class="flex items-center justify-end gap-4">
                    <Link v-if="$page.props.auth.user" :href="dashboard()"
                        class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3e3e3a] dark:text-[#ededec] dark:hover:border-[#62605b]">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="login()"
                            class="inline-block rounded-sm border border-transparent px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#19140035] dark:text-[#ededec] dark:hover:border-[#3e3e3a]">
                            Log in
                        </Link>
                        <Link v-if="canRegister" :href="register()"
                            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3e3e3a] dark:text-[#ededec] dark:hover:border-[#62605b]">
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <main class="flex flex-1 flex-col justify-center gap-10 py-10 lg:gap-16 lg:py-16">
                <section class="relative mt-10 grid items-start gap-10 lg:mt-14 lg:grid-cols-[1fr_1.15fr]">
                    <div
                        class="absolute -inset-x-10 -top-20 -z-10 h-72 bg-linear-to-b from-[#f0e4d7] to-transparent blur-2xl dark:from-[#111111]" />

                    <div class="opacity-100 transition-all duration-700 starting:translate-y-2 starting:opacity-0">
                        <p
                            class="inline-flex items-center rounded-full border border-black/10 bg-white/70 px-3 py-1 text-xs font-medium text-black/70 backdrop-blur dark:border-white/10 dark:bg-white/5 dark:text-white/70">
                            Public demo available
                        </p>
                        <h1 class="mt-4 text-balance text-4xl font-semibold leading-tight tracking-tight lg:text-5xl">
                            Run your inventory operations with a clear, actionable platform.
                        </h1>
                        <p class="mt-4 max-w-xl text-pretty text-base leading-7 text-black/70 dark:text-white/70">
                            Track inbound and outbound stock, define per-item minimums, and make decisions from live
                            KPIs. Designed for small and mid-sized businesses.
                        </p>

                        <div class="mt-7 flex flex-wrap items-center gap-3">
                            <a href="#demo"
                                class="inline-flex items-center rounded-md bg-black px-4 py-2 text-sm font-medium text-white transition hover:bg-black/90 dark:bg-white dark:text-black dark:hover:bg-white/90">
                                See it in action
                            </a>
                            <Link :href="login()"
                                class="inline-flex items-center rounded-md border border-black/15 bg-white px-4 py-2 text-sm font-medium text-black transition hover:bg-black/5 dark:border-white/15 dark:bg-transparent dark:text-white dark:hover:bg-white/5">
                                Try demo free!
                            </Link>
                        </div>


                    </div>

                    <div
                        class="opacity-100 transition-all duration-700 delay-100 starting:translate-y-2 starting:opacity-0 lg:pt-14">
                        <div
                            class="relative overflow-hidden rounded-xl border border-black/10 bg-white shadow-[0_40px_80px_-60px_rgba(0,0,0,0.55)] lg:scale-105 dark:border-white/10 dark:bg-[#0f0f0f]">
                            <img :src="dashboardShot" alt="Main dashboard screenshot" class="h-auto w-full"
                                loading="lazy" decoding="async" />
                        </div>
                        <p class="mt-3 text-sm text-black/60 dark:text-white/60">
                            Real Dashboard Preview
                        </p>
                    </div>
                </section>

                <section class="mt-16 grid gap-6 lg:grid-cols-3">
                    <article
                        class="rounded-xl border border-black/10 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                        <h2 class="text-base font-semibold">No more manual inventory tracking</h2>
                        <ul class="mt-3 space-y-2 text-sm text-black/70 dark:text-white/70">
                            <li>Prevents stockouts with minimum alerts.</li>
                            <li>Reduces manual entry mistakes.</li>
                            <li>Centralizes products, vendors, and clients.</li>
                        </ul>
                    </article>

                    <article
                        class="rounded-xl border border-black/10 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                        <h2 class="text-base font-semibold">Intuitive operation</h2>
                        <ol class="mt-3 space-y-2 text-sm text-black/70 dark:text-white/70">
                            <li>Configure your catalog.</li>
                            <li>Register inbound and outbound stock.</li>
                            <li>Monitor KPIs and alerts.</li>
                        </ol>
                    </article>

                    <article
                        class="rounded-xl border border-black/10 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                        <h2 class="text-base font-semibold">Customizable features</h2>
                        <ul class="mt-3 space-y-2 text-sm text-black/70 dark:text-white/70">
                            <li>Add or remove requirements</li>
                            <li>Personalized approach to your business</li>
                            <li>Professional maintenance.</li>
                        </ul>
                    </article>
                </section>

                <section id="demo" class="mt-16">
                    <h2 class="text-2xl font-semibold tracking-tight">Search products, Manage inventory, Not install needed</h2>
                    <p class="mt-2 max-w-2xl text-sm text-black/70 dark:text-white/70">
                        Dark mode also available.
                    </p>

                    <div class="mt-6">
                        <figure
                            class="overflow-hidden rounded-xl border border-black/10 bg-white dark:border-white/10 dark:bg-white/5">
                            <img :key="currentGifIndex" :src="currentGifSrc" alt="Looped sandbox workflow preview"
                                class="h-auto w-full" loading="lazy" decoding="async" />
                            <figcaption class="px-4 py-3 text-sm text-black/70 dark:text-white/70">
                                {{ currentGifCaption }}
                            </figcaption>
                        </figure>
                    </div>

                    <div
                        class="mt-8 rounded-xl border border-black/10 bg-white p-6 dark:border-white/10 dark:bg-white/5">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <h3 class="text-base font-semibold">Demo access and full license</h3>
                                <p class="mt-1 text-sm text-black/70 dark:text-white/70">
                                    This Demo data resets periodically. Full
                                    persistent version
                                    is sold as a single-tier license. Chages can be apply on demand.
                                </p>
                            </div>
                            <div class="flex gap-3">
                                <a
                                    href="mailto:sales@inventorysys.pro?subject=Product%20purchase%20inquiry"
                                    class="inline-flex items-center rounded-md bg-black px-4 py-2 text-sm font-medium text-white transition hover:bg-black/90 dark:bg-white dark:text-black dark:hover:bg-white/90"
                                >
                                    Contact for purchase
                                </a>
                            </div>
                        </div>
                    </div>
                </section>

                <footer
                    class="mt-16 flex flex-col items-start justify-between gap-3 border-t border-black/10 pt-6 text-sm text-black/60 dark:border-white/10 dark:text-white/60 md:flex-row md:items-center">
                    <a href="mailto:dev@inventorysys.pro" class="transition hover:text-black dark:hover:text-white">
                        fernandoceballos1989@gmail.com
                    </a>
                    <div class="flex items-center gap-4">
                        <a href="https://www.linkedin.com/in/fernando-ceballos-carreras-920825397/" target="_blank" rel="noopener noreferrer"
                            class="transition hover:text-black dark:hover:text-white">
                            LinkedIn
                        </a>
                        <a href="https://github.com/FernandoCeballos1989" target="_blank" rel="noopener noreferrer"
                            class="transition hover:text-black dark:hover:text-white">
                            GitHub
                        </a>
                        <a href="https://your-portfolio.com" target="_blank" rel="noopener noreferrer"
                            class="transition hover:text-black dark:hover:text-white">
                            Portfolio
                        </a>
                    </div>
                </footer>
            </main>
        </div>
    </div>
</template>

