<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="clickClose" width="30%" :close-on-click-modal="false" :show-close="false" append-to-body>
        <div class="row mt-3">
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="control-label">Fecha de orden</label>
                    <el-date-picker v-model="form.issue_date_order" type="date" value-format="yyyy-MM-dd" :clearable="false" ></el-date-picker>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label class="control-label">Referencia de orden</label>
                    <el-input v-model="form.id_order"></el-input>
                </div>
            </div>
        </div>
        <span slot="footer" class="dialog-footer">
            <el-button @click.prevent="clickClose()">Cancelar</el-button>
            <el-button type="primary" @click="clickSave">Guardar</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'order_reference'],

        data() {
            return {
                titleDialog: 'Order reference',
                loading: false,
                resource: 'co-documents',
                errors: {},
                form: {},
                company: {},
                locked_emission: {},
            }
        },

        watch: {
            'form.id_order'(newVal) {
                if(this.form.id_order)
                    this.form.id_order = newVal.toUpperCase();
            },
        },

        async created() {
            if(this.order_reference !== null && this.order_reference !== 'undefined'){
                this.form = this.order_reference
            }
            else{
                this.form = {
                    issue_date_order: null,
                    id_order: null,
                }
            }
        },

        methods: {
            async create() {
            },

            validate(){
                let isValidText = false
                const alpha_num_dash = /^[A-Za-z0-9-;]*$/; // Letras numeros y guiones, opcional

                isValidText = alpha_num_dash.test(this.form.id_order);
                if(!isValidText)
                    return {
                        success: false,
                        message: 'El campo id_order solo debe admite letras, numeros y guiones'
                    }

                if(!this.form.issue_date_order)
                    return {
                        success: false,
                        message: 'El campo fecha de orden es obligatorio'
                    }

                if(!this.form.id_order)
                    return {
                        success: false,
                        message: 'El campo referencia de orden es obligatorio'
                    }

                return {
                    success: true
                }
            },

            clickSave() {
                let validate = this.validate()
                if(!validate.success)
                    return this.$message.error(validate.message)

                this.$emit('addOrderReference', this.form);
                this.close()
            },

            clickClose() {
                this.close()
            },

            close(){
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
