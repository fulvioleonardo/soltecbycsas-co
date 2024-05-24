<template>
    <el-dialog :title="titleDialog" :visible="showDialog" @open="create" width="50%" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false" append-to-body>

        <div class="row mb-4">
            <div class="col-md-3">
                <span>Administracion:</span>
            </div>
            <div class="col-md-3">
                <el-input-number @change="changeValueAdministration('percent')" v-model="form.percent_administartion" ></el-input-number>
            </div>
            <div class="col-md-2">
                <span>% - Valor:</span>
            </div>
            <div class="col-md-4">
                <el-input-number  @change="changeValueAdministration('number')" v-model="form.value_administartion" ></el-input-number>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <span>Imprevisto:</span>
            </div>
            <div class="col-md-3">
                <el-input-number @change="changeValueSudden('percent')" v-model="form.percent_sudden"></el-input-number>
            </div>
            <div class="col-md-2">
                <span>% - Valor:</span>
            </div>
            <div class="col-md-4">
                <el-input-number @change="changeValueSudden('number')" v-model="form.value_sudden" ></el-input-number>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <span>Utilidad:</span>
            </div>
            <div class="col-md-3">
                <el-input-number @change="changeValueUtility('percent')" v-model="form.percent_utility" ></el-input-number>
            </div>
            <div class="col-md-2">
                <span>% - Valor:</span>
            </div>
            <div class="col-md-4">
                <el-input-number @change="changeValueUtility('number')" v-model="form.value_utility" ></el-input-number>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3">
                <span>NOTA AIU:</span>
            </div>
            <div class="col-md-9">
               <el-input
                    type="textarea"
                    :rows="4"
                    v-model="form.note">
                </el-input>
            </div>

        </div>
        <span slot="footer" class="dialog-footer">
            <el-button class="list" @click="save">Aceptar</el-button>
            <el-button class="list" @click="cancel">Cancelar</el-button>
        </span>
    </el-dialog>
</template>

<script>
    export default {
        props: ['showDialog', 'total'],
        data() {
            return {
                titleDialog: null,
                loading: false,
                resource: 'co-documents',
                errors: {},
                form: {},
            }
        },
        async created() {

            this.initForm()

        },
        methods: {
            changeValueAdministration(type)
            {
                if(type == 'percent')
                {
                    this.form.value_administartion = ((this.total * this.form.percent_administartion) / 100)
                }
                else if(type == 'number')
                {
                    this.form.percent_administartion = ((this.form.value_administartion / this.total  ) * 100)
                }
            },
            changeValueSudden(type)
            {
                if(type == 'percent')
                {
                    this.form.value_sudden = ((this.total * this.form.percent_sudden) / 100)
                }
                else if(type == 'number')
                {
                    this.form.percent_sudden = ((this.form.value_sudden / this.total  ) * 100)
                }
            },
            changeValueUtility(type)
            {
                if(type == 'percent')
                {
                    this.form.value_utility = ((this.total * this.form.percent_utility) / 100)
                }
                else if(type == 'number')
                {
                    this.form.percent_utility = ((this.form.value_utility / this.total  ) * 100)
                }
            },
            initForm()
            {
                this.form = {
                    percent_administartion: 0,
                    value_administartion: 0,
                    percent_sudden:0,
                    value_sudden:0,
                    percent_utility:0,
                    value_utility:0,
                    note:null
                }
            },
            create()
            {
                this.titleDialog = `BASE AIU: $ ${this.total}`
            },
            save()
            {
                if(!this.form.note)
                {
                    return this.$message.warning('Debe ingresar el campo nota.');
                }

                this.$emit('add', this.form);
                this.close()

            },
            cancel()
            {
                this.close()
            },
            close() {
                this.$emit('update:showDialog', false)
            },
        }
    }
</script>
