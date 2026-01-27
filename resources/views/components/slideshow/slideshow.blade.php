<section id="fashion-slider"
    class="relative h-screen overflow-hidden bg-black">

    {{-- SLIDES --}}
    <div class="absolute inset-0">
        @foreach ($slides as $index => $slide)
            <div class="fashion-slide absolute inset-0 transition-opacity duration-1000 ease-in-out
                {{ $index === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                data-title="{{ $slide->title }}"
                data-description="{{ $slide->description }}"
                data-url="{{ $slide->url }}"
            >

                <img
                    src="{{ asset('storage/' . $slide->image) }}"
                    class="w-full h-full object-cover scale-105 transition-transform duration-[6000ms]"
                    alt="{{ $slide->title }}"
                >
            </div>
        @endforeach
    </div>

    {{-- OVERLAY --}}
    <div class="absolute inset-0 bg-black/25 z-20"></div>

    {{-- CONTENT --}}
    <div class="relative z-30 h-full flex flex-col items-center justify-end text-white text-center px-6 pb-40 md:pb-32 lg:pb-20">
        <div class="max-w-3xl">
            <p id="slider-subtitle"
               class="text-xs md:text-sm uppercase tracking-[0.45em] mb-4 opacity-0 translate-y-6 transition-all duration-700 shadow-lg">
            </p>

            <h1 id="slider-title" 
                class="text-4xl md:text-5xl font-serif font-bold mb-8 opacity-0 translate-y-8 transition-all duration-700 delay-150 shadow-lg">
            </h1>

            <a id="slider-link" href="{{ route('user.product.index') }}"
               class="inline-block border border-white hover:underline px-10 py-4 text-xs uppercase tracking-widest opacity-0 translate-y-10 transition-all duration-300 delay-100 hover:bg-white hover:text-black"
            >
                Khám phá ngay
            </a>
        </div>
    </div>
</section>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll('.fashion-slide');
    const title = document.getElementById('slider-title');
    const subtitle = document.getElementById('slider-subtitle');
    const link = document.getElementById('slider-link');

    let current = 0;
    const interval = 6000;
    let timer;

    function animateText(slide) {
        // reset
        title.classList.add('opacity-0', 'translate-y-8');
        subtitle.classList.add('opacity-0', 'translate-y-6');
        link.classList.add('opacity-0', 'translate-y-10');

        setTimeout(() => {
            subtitle.textContent = slide.dataset.description ?? '';
            title.textContent = slide.dataset.title ?? '';
            link.href = slide.dataset.url ?? '#';

            subtitle.classList.remove('opacity-0', 'translate-y-6');
            title.classList.remove('opacity-0', 'translate-y-8');
            link.classList.remove('opacity-0', 'translate-y-10');
        }, 300);
    }

    function showSlide(index) {
        slides.forEach((s, i) => {
            s.classList.toggle('opacity-100', i === index);
            s.classList.toggle('opacity-0', i !== index);
            s.classList.toggle('z-10', i === index);
            s.classList.toggle('z-0', i !== index);
        });

        animateText(slides[index]);
    }

    function nextSlide() {
        current = (current + 1) % slides.length;
        showSlide(current);
    }

    if (slides.length) {
        showSlide(0);
        timer = setInterval(nextSlide, interval);
    }

    // Pause on hover (fashion standard)
    const slider = document.getElementById('fashion-slider');
    slider.addEventListener('mouseenter', () => clearInterval(timer));
    slider.addEventListener('mouseleave', () => timer = setInterval(nextSlide, interval));
});
</script>
