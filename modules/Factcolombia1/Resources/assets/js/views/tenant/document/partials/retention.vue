<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" @close="close" top="7vh" :close-on-click-modal="false">
        <form autocomplete="off" @submit.prevent="clickAddItem">
            <div class="form-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group" :class="{'has-danger': errors.tax_id}">
                            <label class="control-label">Retención</label>
                            <el-select v-model="form.tax_id"  filterable>
                                <el-option v-for="option in retentiontaxes" :key="option.id" :value="option.id" :label="`${option.name} - ${option.rate}%`"></el-option>
                            </el-select>
                            <small class="form-control-feedback" v-if="errors.tax_id" v-text="errors.tax_id[0]"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-actions text-right pt-2 mt-3">
                <el-button @click.prevent="close()">Cerrar</el-button>
                <el-button class="add" type="primary" native-type="submit" v-if="form.tax_id">{{titleAction}}</el-button>
            </div>
        </form>
     </el-dialog>
</template>
<style>
.el-select-dropdown {
    max-width: 80% !important;
    margin-right: 5% !important;
}
</style>

<script>

    export default {
        props: ['recordItem','showDialog'],
        data() {
            return {
                titleAction: '',
                titleDialog: '',
                resource: 'co-documents',
                errors: {},
                form: {},
                taxes:[],

            }
        },
        computed: {
            retentiontaxes() {
                return this.taxes.filter(tax => tax.is_retention);
            },
            // retentionSelected() {
            // if (this.retention.retention_id == null) return { rate: 0 };

            //     return this.taxes.find(row => row.id == this.retention.retention_id);
            // },
        },
        created() {
            this.initForm()
            this.$http.get(`/${this.resource}/table/taxes`).then(response => {
                this.taxes = response.data;
            })

        },
        methods: {
            initForm() {

                this.errors = {};

                this.form = {
                    tax_id: null,
                };

            },
            async create() {

                this.titleDialog = 'Agregar retención';
                this.titleAction = 'Agregar';

            },
            close() {
                this.initForm()
                this.$emit('update:showDialog', false)
            },
            async changeItem() {


            },
            async clickAddItem() {

                // this.form = await _.find(this.taxes, {'id': this.form.tax_id})
                // this.form.apply = true
                // console.log(this.form)
                this.$emit('add', this.form);
                this.initForm();

            },
        }
    }

</script>
