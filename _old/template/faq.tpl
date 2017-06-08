<h1><b>FAQ<b></h1></br>
{foreach $faq as $items}
<h3><b>{$items.q}</b></h3>
    <p>{$items.a}</p>
    </br>
    {/foreach}