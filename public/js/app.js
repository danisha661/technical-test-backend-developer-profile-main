const themeSwitcher = {
    // Config
    _scheme: 'auto',
    change: {
        light: '<i>Turn on dark mode</i>',
        dark: '<i>Turn off dark mode</i>',
    },
    itemsTarget: '.theme-switcher',
    localStorageKey: 'preferedColorScheme',

    // Init
    init() {
        this.scheme = this.schemeFromLocalStorage;
        this.initSwitchers();
    },

    // Get color scheme from local storage
    get schemeFromLocalStorage() {
        if (typeof window.localStorage !== 'undefined') {
            if (window.localStorage.getItem(this.localStorageKey) !== null) {
                return window.localStorage.getItem(this.localStorageKey);
            }
        }
        return this._scheme;
    },

    // Prefered color scheme
    get preferedColorScheme() {
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    },

    // Init switchers
    initSwitchers() {
        const items = document.querySelectorAll(this.itemsTarget);
        items.forEach(item => {
            item.addEventListener('click', () => {
                this.scheme == 'dark' ? this.scheme = 'light' : this.scheme = 'dark';
            }, false);
        });
    },

    // Set scheme
    set scheme(scheme) {
        if (scheme == 'auto') {
            this.preferedColorScheme == 'dark' ? this._scheme = 'dark' : this._scheme = 'light';
        }
        else if (scheme == 'dark' || scheme == 'light') {
            this._scheme = scheme;
        }
        this.applyScheme();
        this.schemeToLocalStorage();
    },

    // Get scheme
    get scheme() {
        return this._scheme;
    },

    // Apply scheme
    applyScheme() {
        document.querySelector('html').setAttribute('data-theme', this.scheme);
        const items = document.querySelectorAll(this.itemsTarget);
        items.forEach(
            item => {
                const text = this.scheme == 'dark' ? this.change.dark : this.change.light;
                // item.innerHTML = text;
                item.setAttribute('aria-label', text.replace(/<[^>]*>?/gm, ''));
                
                if (item.type === 'checkbox') {
                    if (this.scheme == 'dark') {
                        item.checked = true
                    }
                    else {
                        item.checked = false
                    }
                }
            }
        );
    },

    // Store scheme to local storage
    schemeToLocalStorage() {
        if (typeof window.localStorage !== 'undefined') {
            window.localStorage.setItem(this.localStorageKey, this.scheme);
        }
    },
};

themeSwitcher.init();
