document.addEventListener('DOMContentLoaded', () => {
    const cartBtn = document.querySelector('.cart');
    const buyBtn = document.querySelector('.buy');
    const msgBtn = document.querySelector('.btn');

    cartBtn.addEventListener('click', () => {
        alert('Produk berhasil ditambahkan ke keranjang!');
        cartBtn.textContent = 'Added ✓';
        setTimeout(() => {
            cartBtn.textContent = 'Add to cart';
        }, 2000);
    });

    buyBtn.addEventListener('click', () => {
        if (confirm('Lanjutkan pembelian seharga 5 Coins?')) {
            alert('Terima kasih! Pembelian Anda sedang diproses.');
        }
    });

    msgBtn.addEventListener('click', () => {
        const message = prompt('Masukkan pesan untuk James Luther:');
        if (message) {
            alert('Pesan terkirim!');
        }
    });
});