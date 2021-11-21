// Require bootstrap javascript files
require('./bootstrap');

// Require the cart dropdown
require('./components/CartDropdown')

// Small easter egg I guess, when clicking on the shopping cart icon 10x it will display a dancing cat
let clicks = 0;
const el = document.getElementById("icon");

if (el) {
    el.onmousedown = (event) => {
        event.preventDefault();
        clicks++;

        if (clicks >= 10) {
            el.innerHTML = `<iframe src="https://giphy.com/embed/PdKTOwHgOASGY" width="449" height="480" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/cat-dancing-what-PdKTOwHgOASGY">via GIPHY</a></p>`
        }
    }
}