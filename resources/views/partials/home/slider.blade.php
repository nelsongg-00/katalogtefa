{{-- Slider Program Keahlian: 1 kartu (mobile), 2 (tablet), 3 (desktop) per tampilan.
     Setiap klik arrow / auto-tick menggeser track tepat satu lebar kartu.
     Loop tak hingga lewat 3 klon (lihat homeSlider() di home.blade.php). --}}
<style>
    /* Mekanika slider mengikuti mockup: --slider-visible = jumlah kartu per tampilan,
       --index = posisi kartu paling kiri (ditulis homeSlider()). */
    [data-slider-track] {
        --slider-visible: 1;
        transform: translateX(calc(var(--index, 0) * -100% / var(--slider-visible)));
        transition: transform 450ms cubic-bezier(0.4, 0, 0.2, 1);
    }

    [data-slider-track] > li {
        flex: 0 0 calc(100% / var(--slider-visible));
    }

    /* Bendakan snap: lompatan pembungkus loop tidak boleh bertransisi. */
    [data-slider-track].is-instant {
        transition: none;
    }

    @media (min-width: 640px) {
        [data-slider-track] {
            --slider-visible: 2;
        }
    }

    @media (min-width: 1024px) {
        [data-slider-track] {
            --slider-visible: 3;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        [data-slider-track] {
            transition: none;
        }
    }
</style>

<div class="relative mx-auto max-w-[1000px] sm:px-12"
     data-slider
     x-data="homeSlider()"
     role="group"
     aria-roledescription="carousel"
     aria-label="Program keahlian"
     @mouseenter="pause()"
     @mouseleave="resume()"
     @focusin="pause()"
     @focusout="resume()">

    <button type="button"
            class="absolute left-2 top-1/2 z-10 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full border border-line bg-white text-ink shadow-arrow sm:left-0"
            data-slider-prev
            aria-label="Kartu sebelumnya"
            @click="prev(); restart();">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="15 18 9 12 15 6" />
        </svg>
    </button>

    {{-- py-5 -my-5 memberi ruang 20px di dalam area potong (overflow-hidden) sehingga
         kartu tidak terpotong saat animasi stagger translateY(20px); jejak layout tetap sama. --}}
    <div class="overflow-hidden py-5 -my-5">
        <ul class="flex"
            data-slider-track
            data-stagger-group
            x-ref="track">
            <li class="px-[0.75rem]">
                <a href="#" class="relative block aspect-[9/16] overflow-hidden rounded-card bg-card-blue">
                    <span class="absolute left-4 top-3 font-display text-5xl leading-none text-white">GIM</span>
                    <span class="sr-only">Program keahlian Pengembangan GIM</span>
                    <!-- TODO: add image (asset/img/program-1.jpg) -->
                </a>
            </li>

            <li class="px-[0.75rem]">
                <a href="#" class="relative block aspect-[9/16] overflow-hidden rounded-card bg-card-orange">
                    <span class="absolute left-4 top-3 font-display text-5xl leading-none text-white">RPL</span>
                    <span class="sr-only">Program keahlian Rekayasa Perangkat Lunak</span>
                    <!-- TODO: add image (asset/img/program-2.jpg) -->
                </a>
            </li>

            <li class="px-[0.75rem]">
                <a href="#" class="relative block aspect-[9/16] overflow-hidden rounded-card bg-card-red">
                    <span class="absolute left-4 top-3 font-display text-5xl leading-none text-white">DKV</span>
                    <span class="sr-only">Program keahlian Desain Komunikasi dan Visual</span>
                    <!-- TODO: add image (asset/img/program-3.jpg) -->
                </a>
            </li>

            <li class="px-[0.75rem]">
                <a href="#" class="relative block aspect-[9/16] overflow-hidden rounded-card bg-card-blue">
                    {{-- TODO: verify from design (label for card 4; design only shows GIM, RPL, DKV) --}}
                    <span class="sr-only">Program keahlian 4</span>
                    <!-- TODO: add image (asset/img/program-4.jpg) + label -->
                </a>
            </li>

            <li class="px-[0.75rem]">
                <a href="#" class="relative block aspect-[9/16] overflow-hidden rounded-card bg-card-orange">
                    {{-- TODO: verify from design (label for card 5) --}}
                    <span class="sr-only">Program keahlian 5</span>
                    <!-- TODO: add image (asset/img/program-5.jpg) + label -->
                </a>
            </li>

            <li class="px-[0.75rem]">
                <a href="#" class="relative block aspect-[9/16] overflow-hidden rounded-card bg-card-red">
                    {{-- TODO: verify from design (label for card 6) --}}
                    <span class="sr-only">Program keahlian 6</span>
                    <!-- TODO: add image (asset/img/program-6.jpg) + label -->
                </a>
            </li>
        </ul>
    </div>

    <button type="button"
            class="absolute right-2 top-1/2 z-10 grid h-9 w-9 -translate-y-1/2 place-items-center rounded-full border border-line bg-white text-ink shadow-arrow sm:right-0"
            data-slider-next
            aria-label="Kartu berikutnya"
            @click="next(); restart();">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="9 18 15 12 9 6" />
        </svg>
    </button>

    <div class="mt-6 flex justify-center gap-2" data-slider-dots>
        <button type="button" class="h-2.5 w-2.5 rounded-full" :class="index % total === 0 ? 'bg-brand' : 'bg-line'" :aria-current="index % total === 0 ? 'true' : null" aria-label="Ke kartu 1" @click="goTo(0); restart();"></button>
        <button type="button" class="h-2.5 w-2.5 rounded-full" :class="index % total === 1 ? 'bg-brand' : 'bg-line'" :aria-current="index % total === 1 ? 'true' : null" aria-label="Ke kartu 2" @click="goTo(1); restart();"></button>
        <button type="button" class="h-2.5 w-2.5 rounded-full" :class="index % total === 2 ? 'bg-brand' : 'bg-line'" :aria-current="index % total === 2 ? 'true' : null" aria-label="Ke kartu 3" @click="goTo(2); restart();"></button>
        <button type="button" class="h-2.5 w-2.5 rounded-full" :class="index % total === 3 ? 'bg-brand' : 'bg-line'" :aria-current="index % total === 3 ? 'true' : null" aria-label="Ke kartu 4" @click="goTo(3); restart();"></button>
        <button type="button" class="h-2.5 w-2.5 rounded-full" :class="index % total === 4 ? 'bg-brand' : 'bg-line'" :aria-current="index % total === 4 ? 'true' : null" aria-label="Ke kartu 5" @click="goTo(4); restart();"></button>
        <button type="button" class="h-2.5 w-2.5 rounded-full" :class="index % total === 5 ? 'bg-brand' : 'bg-line'" :aria-current="index % total === 5 ? 'true' : null" aria-label="Ke kartu 6" @click="goTo(5); restart();"></button>
    </div>
</div>