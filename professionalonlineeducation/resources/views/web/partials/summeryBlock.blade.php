<span id="newTotalValue" data-total="{{ $appliedDiscount ? $newTotal : $total }}"></span>
@if(!empty($error))
    <p class="text-danger" style="margin-bottom:10px;">{{ $error }}</p>
@endif
<ul class="checkoutSummery">
    <li>
        <b>Sub Total: </b>
        <span class="currency-price" data-usd="{{ $total }}">
            <span class="curr-symbol">$</span><span class="curr-amount">{{ $total }}</span>
        </span>
    </li>
    @if($appliedDiscount != false)
        <li>
            <b>Discount:</b>
            -<span class="currency-price" data-usd="{{ abs($newTotal - $total) }}">
                <span class="curr-symbol">$</span><span class="curr-amount">{{ abs($newTotal - $total) }}</span>
            </span>
        </li>
        <li>
            <b>Total:</b>
            <span class="currency-price" data-usd="{{ $newTotal }}">
                <span class="curr-symbol">$</span><span class="curr-amount">{{ $newTotal }}</span>
            </span>
        </li>
    @else
        <li>
            <b>Total:</b>
            <span class="currency-price" data-usd="{{ $total }}">
                <span class="curr-symbol">$</span><span class="curr-amount">{{ $total }}</span>
            </span>
        </li>
    @endif

</ul>
