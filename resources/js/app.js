window.Vue = require('vue');

import algoliasearch from 'algoliasearch/lite';
import moment from 'moment/moment';
import tippy from 'tippy.js';

window.algoliasearch = algoliasearch;

import InstantSearch from 'vue-instantsearch';

Vue.use(InstantSearch);

require('./bootstrap');
require('./pikaday');
require('./popper');
require('./tippy');

document.addEventListener("livewire:update", () => {
    window.Alpine.discoverUninitializedComponents((el) => {
        window.Alpine.initializeComponent(el);
    });
});

// window.onload = function(){
//     document.getElementById("page-loading").style.opacity = "0";
//     setTimeout(function(){
//         document.getElementById("page-loading").style.display = "none";
//     }, 100)
// };

// Components
window.Components = {
    customSelect(options) {
        return {
            init() {
                this.optionCount = this.$refs.listbox.children.length;
                this.$watch('selected', (value) => {
                    if (!this.open) return;

                    if (this.selected === null) {
                        this.activeDescendant = '';
                        return;
                    }

                    this.activeDescendant = this.$refs.listbox.children[this.selected].id;
                });
            },
            activeDescendant: null,
            optionCount: null,
            open: false,
            selected: null,
            value: 0,
            choose(option) {
                this.value = option;
                this.open = false;
            },
            onButtonClick() {
                if (this.open) return;
                this.selected = this.value;
                this.open = true;
                this.$nextTick(() => {
                    this.$refs.listbox.focus();
                    this.$refs.listbox.children[this.selected].scrollIntoView({block: 'nearest'});
                });
            },
            onOptionSelect() {
                if (this.selected !== null) {
                    this.value = this.selected;
                }
                this.open = false;
                this.$refs.button.focus();
            },
            onEscape() {
                this.open = false;
                this.$refs.button.focus();
            },
            onArrowUp() {
                this.selected = this.selected - 1 < 0 ? this.optionCount - 1 : this.selected - 1;
                this.$refs.listbox.children[this.selected].scrollIntoView({block: 'nearest'});
            },
            onArrowDown() {
                this.selected = this.selected + 1 > this.optionCount - 1 ? 1 : this.selected + 1;
                this.$refs.listbox.children[this.selected].scrollIntoView({block: 'nearest'});
            },
            ...options,
        };
    },
};

window.Components.radioGroup = function ({initialCheckedIndex: t = 0} = {}) {
    return {
        value: void 0,
        init() {
            this.value = Array.from(this.$el.querySelectorAll("input"))[t]?.value;
        }
    };
};

function tooltips(){
    let tooltipTriggerList = document.querySelectorAll('[tooltip]')
    tooltipTriggerList .forEach((tool) => {
        if(tool.getAttribute('tooltip-p')){
            var placement = tool.getAttribute('tooltip-p');

        }else{
            var placement = 'top';
        }
        tippy(tool, {
            content: tool.getAttribute('tooltip'),
            placement: placement,
            animation: 'shift-away-subtle',
            delay: [500, 0], // ms
        });
    });
}
Livewire.hook('component.initialized', component => {tooltips()});



