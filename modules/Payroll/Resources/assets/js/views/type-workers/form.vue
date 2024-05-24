<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @close="close" @open="create" append-to-body top="7vh">
        <form autocomplete="off" @submit.prevent="submit">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group" :class="{'has-danger': errors.code}">
                            <label class="control-label">Código 
                            </label>
                            <el-input v-model="form.code" dusk="code"></el-input>
                            <small class="form-control-feedback" v-if="errors.code" v-text="errors.code[0]"></small>
                        </div>
                    </div> 
                    <div class="col-md-8">
                        <div class="form-group" :class="{'has-danger': errors.name}">
                            <label class="control-label">Nombre</label>
                            <el-input v-model="form.name" dusk="name"></el-input>
                            <small class="form-control-feedback" v-if="errors.name" v-text="errors.name[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right mt-4">
                <el-button @click.prevent="close()">Cancelar</el-button>
                <el-button type="primary" native-type="submit" :loading="loading_submit">Guardar</el-button>
            </div>
        </form>  

    </el-dialog>
</template>

<script>

    export default {
        props: ['showDialog', 'recordId'],

        data() {
            return { 
                resource: 'payroll/type-workers',
                errors: {},
                form: {},
                loading_submit : false,
                titleDialog:null
            }
        },
        async created() {
            await this.initForm()
        },

        methods: {
            initForm() {

                this.errors = {}

                this.form = {
                    id: null,
                    code: null,
                    name: null,
                }

            },
            create() {

                this.titleDialog = (this.recordId)? 'Editar tipo empleado':'Nuevo tipo empleado'
                this.getRecord()

            },
            getRecord(){
                if (this.recordId) {
                    this.$http.get(`/${this.resource}/record/${this.recordId}`)
                        .then(response => {
                            this.form = response.data.data
                        })
                }
            },
            async submit() { 

                this.loading_submit = true
                await this.$http.post(`/${this.resource}`, this.form)
                    .then(response => {
                        if (response.data.success) {
                            this.$message.success(response.data.message)
                            this.$eventHub.$emit('reloadData')
                            this.close()
                        } else {
                            this.$message.error(response.data.message)
                        }
                    })
                    .catch(error => {
                        if (error.response.status === 422) {
                            this.errors = error.response.data
                        } else {
                            console.log(error)
                        }
                    })
                    .then(() => {
                        this.loading_submit = false
                    })
            },
            close() {
                this.$emit('update:showDialog', false)
                this.initForm()
            }
        }
    }
</script>
