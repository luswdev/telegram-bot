$(document).ready( () => {
    let navBarItem = {
        props: ['current', 'name', 'url'],
        template: `
            <li class="nav-item">
                <a class="nav-link" v-bind:class="[current==name ? 'active' : ''] " v-bind:href="current==name ? '#' : url">
                    {{name}}
                </a>
            </li>
        `
    }

    let navBarLink = {
        props: ['icon', 'url'],
        template: `
            <li class="nav-item">
                    <a class="nav-link" v-bind:href="url" target="_blank">
                        <i v-bind:class="icon"></i>
                    </a>
                </li>
            </ul>
        `
    }

    let headerVue = new Vue({ 
        el: '#vue-header',
        components: {
            'nav-bar-item': navBarItem,
            'nav-bar-link': navBarLink
        }
    })

    let copyright = {
        template: `
            <div class="footer-inner container text-black-50">
                &copy; {{new Date().getFullYear()}} LuSkywalker
            </div>
        `
    }

    let footerVue = new Vue({ 
        el: '#vue-footer',
        components: {
            'copyright': copyright
        }
    })
});