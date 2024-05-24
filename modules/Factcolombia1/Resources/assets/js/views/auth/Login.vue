<template>
    <v-app id="login">
        <v-content>
            <v-container fluid fill-height>
                <v-layout align-center justify-center>
                    <v-flex xs12 sm8 md4>
                        <v-form ref="form" :method="method" :action="action" @submit="validate">
                            <input type="hidden" name="_token" :value="csrf">

                            <div class="logo justify-content-center text-primary">
                                <!-- <img src="factcolombia/assets/images/logo_bee.png" alt="" style="min-width: 150px;"> -->
                            </div>
                            <div class="box needs-validation">
                                <div class="box-header justify-content-center">
                                    <h3>Iniciar sesión</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        <v-text-field type="text" name="email" v-model="email" v-validate="'required|email|max:50'" :error-messages="errors.collect('email')" data-vv-name="email" label="Email" required></v-text-field>
                                    </div>
                                    <div class="form-group">
                                        <v-text-field type="password" name="password" v-model="password" v-validate="'required|max:20'" :error-messages="errors.collect('password')" data-vv-name="password" label="Contraseña" required></v-text-field>
                                    </div>
                                    <div class="form-group">
                                        <div class="btn-group btn-group-stretch">
                                            <v-btn type="submit" class="bee darker text-white no-decoration" :loading="loading">Iniciar sesión</v-btn>
                                        </div>
                                    </div>
                                    <div class="text-center pt-3">
                                        <p>© Copyright 2019. Todos los derechos reservados</p>
                                    </div>

                                    <!-- <p class="text-center my-0"><a href="page-forgot-password.html">Forgot Password?</a></p> -->
                                </div>
                            </div>
                        </v-form>
                    </v-flex>
                </v-layout>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    export default {
        props: {
            'method': {
                required: false,
                default: 'POST'
            },
            'action': {
                required: false,
                default: '/login'
            },
            'csrf': {
                required: false,
                default: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        },
        data: () => ({
            email: '',
            password: '',
            loading: false
        }),
        methods: {
            validate(event) {
                this.loading = true;

                this.$validator.validateAll().then(valid => {
                    if (!valid) {
                        event.preventDefault();

                        this.loading = false;
                    }
                });
            }
        }
    }
</script>
