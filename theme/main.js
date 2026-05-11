document.addEventListener('click', function(e) {
    const btn = e.target.closest('[data-toggle-fav]');
    if (!btn) return;

    const productId = btn.dataset.productId;
    const action    = btn.dataset.favAction;

    fetch(wpAjax.url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
            action:      'toggle_favorite',
            product_id:  productId,
            fav_action:  action,
            nonce:       wpAjax.nonce
        })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) return;

        if (action === 'add') {
            btn.textContent          = '♥ Remove';
            btn.dataset.favAction    = 'remove';
            btn.classList.replace('btn-add', 'btn-remove');
        } else {
            // on shop page — toggle back to Save
            if (!btn.dataset.favItem) {
                btn.textContent       = '♡ Save';
                btn.dataset.favAction = 'add';
                btn.classList.replace('btn-remove', 'btn-add');
            }
            // on favorites page — remove the card
            btn.closest('.fav-item')?.remove();
        }
    });
});