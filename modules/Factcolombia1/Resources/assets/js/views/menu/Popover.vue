<template>
    <div>
        <li>
            <a href="#" data-dropdown="" class="user-panel" style="text-decoration: none;">
                <div class="user-panel-image">
                    <div class="">
                        <i class="fe fe-user" style="font-size: 37px; background: #727575; height: 50px; width: 50px;  border-radius: 100px;"></i>
                    </div>
                </div>
                <div class="user-panel-info">
                    <p class="text-primary">{{user.name}}</p>
                    <small class="text-black-50">{{user.email}}</small>
                </div>
            </a>
            <ul class="dropdown-menu">
                <!-- <li><a class="dropdown-item" href="page-profile.html"><i class="far fa-user"></i> Profile</a></li>
                <li class="dropdown-divider"></li> -->
                <li class="py-2 px-2">
                    <v-form :method="method" :action="action">
                        <input type="hidden" name="_token" :value="csrf">
                        <button type="submit"><i class="fas fa-power-off"></i> Cerrar sesión</button>
                    </v-form>
                </li>
            </ul>
        </li>
    </div>
</template>

<script>
    export default {
        props: {
            user: {
                required: true
            },
            'method': {
                required: false,
                default: 'POST'
            },
            'action': {
                required: false,
                default: '/logout'
            },
            'csrf': {
                required: false,
                default: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        data: () => ({
            message: true,
            menu: false,
            hints: true,
            theme: true,
        }),
        methods: {
            updateTheme(val) {
                EventBus.$emit('updateTheme', val);
            }
        }
    }
</script>
