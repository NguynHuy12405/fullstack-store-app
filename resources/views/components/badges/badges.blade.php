props(['product'])

@php
    $isNew = $product->created_at->diffInDays(now()) < 7;
@endphp

<div class="absolute top-3 left-3 flex flex-col gap-2 pointer-events-none">
    @if($product->view >= 1000)
        <span class="bg-black text-white px-2 py-1 text-[10px] font-bold uppercase tracking-wider">Best Seller</span>
    @elseif($product->view >= 200)
        <span class="bg-yellow-500 text-white px-2 py-1 text-[10px] font-bold uppercase tracking-wider">Hot</span>
    @elseif($isNew)
        <span class="bg-white text-black px-2 py-1 text-[10px] font-bold uppercase tracking-wider shadow-sm">New</span>
    @elseif($product->variants->first() && $product->variants->first()->quantity <= 0)
        <span class="bg-red-600 text-white px-2 py-1 text-[10px] font-bold uppercase tracking-wider shadow-sm">Sold Out</span>
    @endif
</div>