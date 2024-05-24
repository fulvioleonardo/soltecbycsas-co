<template>
    <el-dialog :title="titleDialog" width="70%" :visible="showDialog" @open="create" :close-on-click-modal="false" :close-on-press-escape="false" :show-close="false" append-to-body>

        <el-tabs v-model="activeName">
            <el-tab-pane label="Horas extras diurnas (25%)" name="heds">
                    
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr width="100%">
                                    <template v-if="form.accrued.heds.length>0">
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Hora inicio - Hora término</th>
                                        <th class="pb-2">Cantidad</th>
                                        <!-- <th class="pb-2">Porcentaje</th> -->
                                        <th class="pb-2">Pago</th>
                                    </template>
                                    <th width="10%"><a href="#" @click.prevent="clickAddExtraHour(form.accrued.heds, 'heds')" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in form.accrued.heds" :key="index"> 
                                    <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-date-picker 
                                                v-model="row.start_end_date" 
                                                type="date"
                                                value-format="yyyy-MM-dd" 
                                                :clearable="false"
                                                @change="changeStartEndDate(form.accrued.heds, index)"
                                            ></el-date-picker>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <template v-if="errors[`accrued.heds.${index}.start_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heds.${index}.start_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heds.${index}.start_time`][0]"></small>
                                            </div>
                                        </template>
                                        <template v-if="errors[`accrued.heds.${index}.end_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heds.${index}.end_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heds.${index}.end_time`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            
                                            <el-time-picker
                                                is-range
                                                v-model="row.start_end_time"
                                                range-separator="H"
                                                format="HH:mm"
                                                value-format="HH:mm"
                                                :clearable="false"
                                                @change="changeStartEndTime(form.accrued.heds, index, 'heds')"
                                                >
                                            </el-time-picker>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.heds.${index}.quantity`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heds.${index}.quantity`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heds.${index}.quantity`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.quantity" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>
                                    <!-- <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.percentage" :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td> -->
                                    <td>
                                        
                                        <template v-if="errors[`accrued.heds.${index}.payment`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heds.${index}.payment`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heds.${index}.payment`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.payment" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>

                                    <td class="series-table-actions text-center">
                                        
                                        <!-- validacion porcentaje -->
                                        <template v-if="errors[`accrued.heds.${index}.percentage`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heds.${index}.percentage`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heds.${index}.percentage`][0]"></small>
                                            </div>
                                        </template>
                                        <!-- validacion porcentaje -->

                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelExtraHour(form.accrued.heds, index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Horas extras nocturnas (75%)" name="hens">
                    
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr width="100%">
                                    <template v-if="form.accrued.hens.length>0">
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Hora inicio - Hora término</th>
                                        <th class="pb-2">Cantidad</th>
                                        <th class="pb-2">Pago</th>
                                    </template>
                                    <th width="10%"><a href="#" @click.prevent="clickAddExtraHour(form.accrued.hens, 'hens')" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in form.accrued.hens" :key="index"> 
                                    <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-date-picker 
                                                v-model="row.start_end_date" 
                                                type="date"
                                                value-format="yyyy-MM-dd" 
                                                :clearable="false"
                                                @change="changeStartEndDate(form.accrued.hens, index)"
                                            ></el-date-picker>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hens.${index}.start_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hens.${index}.start_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hens.${index}.start_time`][0]"></small>
                                            </div>
                                        </template>
                                        <template v-if="errors[`accrued.hens.${index}.end_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hens.${index}.end_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hens.${index}.end_time`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            
                                            <el-time-picker
                                                is-range
                                                v-model="row.start_end_time"
                                                range-separator="H"
                                                format="HH:mm"
                                                value-format="HH:mm"
                                                :clearable="false"
                                                @change="changeStartEndTime(form.accrued.hens, index, 'hens')"
                                                >
                                            </el-time-picker>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hens.${index}.quantity`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hens.${index}.quantity`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hens.${index}.quantity`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.quantity" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hens.${index}.payment`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hens.${index}.payment`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hens.${index}.payment`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.payment" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>

                                    <td class="series-table-actions text-center">
                                        
                                        <!-- validacion porcentaje -->
                                        <template v-if="errors[`accrued.hens.${index}.percentage`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hens.${index}.percentage`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hens.${index}.percentage`][0]"></small>
                                            </div>
                                        </template>
                                        <!-- validacion porcentaje -->

                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelExtraHour(form.accrued.hens, index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Horas Recargo Nocturno (35%)" name="hrns">
                    
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr width="100%">
                                    <template v-if="form.accrued.hrns.length>0">
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Hora inicio - Hora término</th>
                                        <th class="pb-2">Cantidad</th>
                                        <th class="pb-2">Pago</th>
                                    </template>
                                    <th width="10%"><a href="#" @click.prevent="clickAddExtraHour(form.accrued.hrns, 'hrns')" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in form.accrued.hrns" :key="index"> 
                                    <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-date-picker 
                                                v-model="row.start_end_date" 
                                                type="date"
                                                value-format="yyyy-MM-dd" 
                                                :clearable="false"
                                                @change="changeStartEndDate(form.accrued.hrns, index)"
                                            ></el-date-picker>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrns.${index}.start_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrns.${index}.start_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrns.${index}.start_time`][0]"></small>
                                            </div>
                                        </template>
                                        <template v-if="errors[`accrued.hrns.${index}.end_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrns.${index}.end_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrns.${index}.end_time`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            
                                            <el-time-picker
                                                is-range
                                                v-model="row.start_end_time"
                                                range-separator="H"
                                                format="HH:mm"
                                                value-format="HH:mm"
                                                :clearable="false"
                                                @change="changeStartEndTime(form.accrued.hrns, index, 'hrns')"
                                                >
                                            </el-time-picker>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrns.${index}.quantity`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrns.${index}.quantity`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrns.${index}.quantity`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.quantity" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrns.${index}.payment`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrns.${index}.payment`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrns.${index}.payment`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.payment" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>

                                    <td class="series-table-actions text-center">
                                        
                                        <!-- validacion porcentaje -->
                                        <template v-if="errors[`accrued.hrns.${index}.percentage`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrns.${index}.percentage`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrns.${index}.percentage`][0]"></small>
                                            </div>
                                        </template>
                                        <!-- validacion porcentaje -->

                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelExtraHour(form.accrued.hrns, index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Horas Extras Diurnas Dominical y Festivos (100%)" name="heddfs">
                    
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr width="100%">
                                    <template v-if="form.accrued.heddfs.length>0">
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Hora inicio - Hora término</th>
                                        <th class="pb-2">Cantidad</th>
                                        <th class="pb-2">Pago</th>
                                    </template>
                                    <th width="10%"><a href="#" @click.prevent="clickAddExtraHour(form.accrued.heddfs, 'heddfs')" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in form.accrued.heddfs" :key="index"> 
                                    <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-date-picker 
                                                v-model="row.start_end_date" 
                                                type="date"
                                                value-format="yyyy-MM-dd" 
                                                :clearable="false"
                                                @change="changeStartEndDate(form.accrued.heddfs, index)"
                                            ></el-date-picker>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <template v-if="errors[`accrued.heddfs.${index}.start_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heddfs.${index}.start_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heddfs.${index}.start_time`][0]"></small>
                                            </div>
                                        </template>
                                        <template v-if="errors[`accrued.heddfs.${index}.end_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heddfs.${index}.end_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heddfs.${index}.end_time`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            
                                            <el-time-picker
                                                is-range
                                                v-model="row.start_end_time"
                                                range-separator="H"
                                                format="HH:mm"
                                                value-format="HH:mm"
                                                :clearable="false"
                                                @change="changeStartEndTime(form.accrued.heddfs, index, 'heddfs')"
                                                >
                                            </el-time-picker>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.heddfs.${index}.quantity`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heddfs.${index}.quantity`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heddfs.${index}.quantity`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.quantity" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.heddfs.${index}.payment`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heddfs.${index}.payment`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heddfs.${index}.payment`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.payment" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>

                                    <td class="series-table-actions text-center">
                                        
                                        <!-- validacion porcentaje -->
                                        <template v-if="errors[`accrued.heddfs.${index}.percentage`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.heddfs.${index}.percentage`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.heddfs.${index}.percentage`][0]"></small>
                                            </div>
                                        </template>
                                        <!-- validacion porcentaje -->

                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelExtraHour(form.accrued.heddfs, index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </el-tab-pane>
            
            <el-tab-pane label="Horas Recargo Diurno Dominical y Festivos (75%)" name="hrddfs">
                    
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr width="100%">
                                    <template v-if="form.accrued.hrddfs.length>0">
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Hora inicio - Hora término</th>
                                        <th class="pb-2">Cantidad</th>
                                        <th class="pb-2">Pago</th>
                                    </template>
                                    <th width="10%"><a href="#" @click.prevent="clickAddExtraHour(form.accrued.hrddfs, 'hrddfs')" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in form.accrued.hrddfs" :key="index"> 
                                    <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-date-picker 
                                                v-model="row.start_end_date" 
                                                type="date"
                                                value-format="yyyy-MM-dd" 
                                                :clearable="false"
                                                @change="changeStartEndDate(form.accrued.hrddfs, index)"
                                            ></el-date-picker>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrddfs.${index}.start_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrddfs.${index}.start_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrddfs.${index}.start_time`][0]"></small>
                                            </div>
                                        </template>
                                        <template v-if="errors[`accrued.hrddfs.${index}.end_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrddfs.${index}.end_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrddfs.${index}.end_time`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            
                                            <el-time-picker
                                                is-range
                                                v-model="row.start_end_time"
                                                range-separator="H"
                                                format="HH:mm"
                                                value-format="HH:mm"
                                                :clearable="false"
                                                @change="changeStartEndTime(form.accrued.hrddfs, index, 'hrddfs')"
                                                >
                                            </el-time-picker>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrddfs.${index}.quantity`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrddfs.${index}.quantity`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrddfs.${index}.quantity`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.quantity" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrddfs.${index}.payment`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrddfs.${index}.payment`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrddfs.${index}.payment`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.payment" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>

                                    <td class="series-table-actions text-center">
                                        
                                        <!-- validacion porcentaje -->
                                        <template v-if="errors[`accrued.hrddfs.${index}.percentage`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrddfs.${index}.percentage`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrddfs.${index}.percentage`][0]"></small>
                                            </div>
                                        </template>
                                        <!-- validacion porcentaje -->

                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelExtraHour(form.accrued.hrddfs, index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Horas Extras Nocturnas Dominical y Festivos (150%)" name="hendfs">
                    
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr width="100%">
                                    <template v-if="form.accrued.hendfs.length>0">
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Hora inicio - Hora término</th>
                                        <th class="pb-2">Cantidad</th>
                                        <th class="pb-2">Pago</th>
                                    </template>
                                    <th width="10%"><a href="#" @click.prevent="clickAddExtraHour(form.accrued.hendfs, 'hendfs')" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in form.accrued.hendfs" :key="index"> 
                                    <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-date-picker 
                                                v-model="row.start_end_date" 
                                                type="date"
                                                value-format="yyyy-MM-dd" 
                                                :clearable="false"
                                                @change="changeStartEndDate(form.accrued.hendfs, index)"
                                            ></el-date-picker>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hendfs.${index}.start_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hendfs.${index}.start_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hendfs.${index}.start_time`][0]"></small>
                                            </div>
                                        </template>
                                        <template v-if="errors[`accrued.hendfs.${index}.end_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hendfs.${index}.end_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hendfs.${index}.end_time`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            
                                            <el-time-picker
                                                is-range
                                                v-model="row.start_end_time"
                                                range-separator="H"
                                                format="HH:mm"
                                                value-format="HH:mm"
                                                :clearable="false"
                                                @change="changeStartEndTime(form.accrued.hendfs, index, 'hendfs')"
                                                >
                                            </el-time-picker>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hendfs.${index}.quantity`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hendfs.${index}.quantity`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hendfs.${index}.quantity`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.quantity" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hendfs.${index}.payment`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hendfs.${index}.payment`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hendfs.${index}.payment`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.payment" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>

                                    <td class="series-table-actions text-center">
                                        
                                        <!-- validacion porcentaje -->
                                        <template v-if="errors[`accrued.hendfs.${index}.percentage`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hendfs.${index}.percentage`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hendfs.${index}.percentage`][0]"></small>
                                            </div>
                                        </template>
                                        <!-- validacion porcentaje -->

                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelExtraHour(form.accrued.hendfs, index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </el-tab-pane>

            <el-tab-pane label="Horas Recargo Nocturno Dominical y Festivos (110%)" name="hrndfs">
                    
                <div class="row mt-2">
                    <div class="col-md-12">
                        <table>
                            <thead>
                                <tr width="100%">
                                    <template v-if="form.accrued.hrndfs.length>0">
                                        <th class="pb-2">Fecha</th>
                                        <th class="pb-2">Hora inicio - Hora término</th>
                                        <th class="pb-2">Cantidad</th>
                                        <th class="pb-2">Pago</th>
                                    </template>
                                    <th width="10%"><a href="#" @click.prevent="clickAddExtraHour(form.accrued.hrndfs, 'hrndfs')" class="text-center font-weight-bold text-info">[+ Agregar]</a></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(row, index) in form.accrued.hrndfs" :key="index"> 
                                    <td>
                                        <div class="form-group mb-2 mr-2"  >
                                            <el-date-picker 
                                                v-model="row.start_end_date" 
                                                type="date"
                                                value-format="yyyy-MM-dd" 
                                                :clearable="false"
                                                @change="changeStartEndDate(form.accrued.hrndfs, index)"
                                            ></el-date-picker>
                                        </div>
                                    </td>
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrndfs.${index}.start_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrndfs.${index}.start_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrndfs.${index}.start_time`][0]"></small>
                                            </div>
                                        </template>
                                        <template v-if="errors[`accrued.hrndfs.${index}.end_time`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrndfs.${index}.end_time`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrndfs.${index}.end_time`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            
                                            <el-time-picker
                                                is-range
                                                v-model="row.start_end_time"
                                                range-separator="H"
                                                format="HH:mm"
                                                value-format="HH:mm"
                                                :clearable="false"
                                                @change="changeStartEndTime(form.accrued.hrndfs, index, 'hrndfs')"
                                                >
                                            </el-time-picker>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrndfs.${index}.quantity`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrndfs.${index}.quantity`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrndfs.${index}.quantity`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.quantity" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td> 
                                    <td>
                                        
                                        <template v-if="errors[`accrued.hrndfs.${index}.payment`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrndfs.${index}.payment`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrndfs.${index}.payment`][0]"></small>
                                            </div>
                                        </template>

                                        <div class="form-group mb-2 mr-2"  >
                                            <el-input-number v-model="row.payment" disabled :min="0" controls-position="right"></el-input-number>
                                        </div>
                                    </td>

                                    <td class="series-table-actions text-center">
                                        
                                        <!-- validacion porcentaje -->
                                        <template v-if="errors[`accrued.hrndfs.${index}.percentage`]">
                                            <div class="form-group" :class="{'has-danger': errors[`accrued.hrndfs.${index}.percentage`]}">
                                                <small class="form-control-feedback" v-text="errors[`accrued.hrndfs.${index}.percentage`][0]"></small>
                                            </div>
                                        </template>
                                        <!-- validacion porcentaje -->

                                        <button  type="button" class="btn waves-effect waves-light btn-xs btn-danger" @click.prevent="clickCancelExtraHour(form.accrued.hrndfs, index)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </el-tab-pane>

        </el-tabs>

        <span slot="footer" class="dialog-footer">
            <el-button @click="clickClose">Cerrar</el-button>
        </span>
    </el-dialog>
</template>

<script>
import moment from 'moment'
    export default {
        props: ['showDialog', 'form', 'errors'],
        data() {
            return {
                titleDialog: 'Horas extras',
                loading: false,
                activeName: 'heds',
                resource: 'payroll/document-payrolls',
                extra_hour_types: [],
                type_overtime_surcharges: [],
            }
        },
        async created() {
            await this.initData()
            await this.getTable()
        },
        methods: {
            getTable(){
                this.$http.get(`/${this.resource}/table/type_overtime_surcharges`).then((response) => {
                    this.type_overtime_surcharges = response.data
                })
            },
            initData(){

            },
            changeStartEndTime(array_hours, index, type){
                // console.log(array_hours[index].start_end_time)

                // calcular cantidad horas y valores adicionales
                this.calculateExtraHours(array_hours, index, type)

            },
            calculateExtraHours(array_hours, index, type){

                this.calculateValuesExtraHours(array_hours, index, type)

                this.setStartEndTimeFormat(array_hours, index)

                // this.sumTotalsExtraHours()
            },
            calculateValuesExtraHours(array_hours, index, type){

                const start_end_time = array_hours[index].start_end_time
                let start_time = start_end_time[0]
                let end_time = start_end_time[1]
                
                let quantity = _.round(moment(end_time, "HH:mm:ss").diff(moment(start_time, "HH:mm:ss"), 'hours', true), 2)

                let type_overtime_surcharge = this.getTypeOvertimeSurcharge(type)
                let percentage_id = type_overtime_surcharge.id
                let price_per_hour_extra = this.getPricePerExtraHour(type_overtime_surcharge.percentage)

                //calcular valores finales
                array_hours[index].quantity = quantity
                array_hours[index].percentage = percentage_id
                array_hours[index].payment =  _.round(price_per_hour_extra * quantity, 2)

            },
            recalculateDataExtraHours(){

                // usado cuando se modifica el salario base, ya que el campo pagos de las horas extras varian en base al mismo
                
                this.form.accrued.heds.forEach((item, index) => {
                    this.calculateValuesExtraHours(this.form.accrued.heds, index, 'heds')
                })

                this.form.accrued.hens.forEach((item, index) => {
                    this.calculateValuesExtraHours(this.form.accrued.hens, index, 'hens')
                })
                
                this.form.accrued.hrns.forEach((item, index) => {
                    this.calculateValuesExtraHours(this.form.accrued.hrns, index, 'hrns')
                })
                
                this.form.accrued.heddfs.forEach((item, index) => {
                    this.calculateValuesExtraHours(this.form.accrued.heddfs, index, 'heddfs')
                })
                
                this.form.accrued.hrddfs.forEach((item, index) => {
                    this.calculateValuesExtraHours(this.form.accrued.hrddfs, index, 'hrddfs')
                })
                
                this.form.accrued.hendfs.forEach((item, index) => {
                    this.calculateValuesExtraHours(this.form.accrued.hendfs, index, 'hendfs')
                })
                
                this.form.accrued.hrndfs.forEach((item, index) => {
                    this.calculateValuesExtraHours(this.form.accrued.hrndfs, index, 'hrndfs')
                })

                //sumar todos los pagos de las horas extras
                this.sumTotalsExtraHours()

            },
            sumTotalsExtraHours(){

                let total_heds = this.getTotalPaymentExtraHours(this.form.accrued.heds)
                let total_hens = this.getTotalPaymentExtraHours(this.form.accrued.hens)
                let total_hrns = this.getTotalPaymentExtraHours(this.form.accrued.hrns)
                let total_heddfs = this.getTotalPaymentExtraHours(this.form.accrued.heddfs)
                let total_hrddfs = this.getTotalPaymentExtraHours(this.form.accrued.hrddfs)
                let total_hendfs = this.getTotalPaymentExtraHours(this.form.accrued.hendfs)
                let total_hrndfs = this.getTotalPaymentExtraHours(this.form.accrued.hrndfs)

                this.form.accrued.total_extra_hours = _.round(total_heds + total_hens + total_hrns + total_heddfs + total_hrddfs + total_hendfs + total_hrndfs, 2)

            },
            getTotalPaymentExtraHours(array_hours){
                return _.sumBy(array_hours, 'payment')
            },
            changeStartEndDate(array_hours, index){
                this.setStartEndTimeFormat(array_hours, index)
            },
            setStartEndTimeFormat(array_hours, index){

                //arreglo generado por el componente time-picker
                const start_end_time = array_hours[index].start_end_time

                let obj_start_end_time = this.getStartEndTimeFormatApi(array_hours[index].start_end_date, start_end_time[0], start_end_time[1])

                // asignar campos hora inicio y termino para enviar a api de acuerdo al formato
                array_hours[index].start_time = obj_start_end_time.start_time
                array_hours[index].end_time = obj_start_end_time.end_time

            },
            getStartEndTimeFormatApi(param_start_end_date, param_start_time, param_end_time){

                let start_time = `${param_start_end_date} ${param_start_time}:00`
                let end_time = `${param_start_end_date} ${param_end_time}:00`

                return {
                    start_time: moment(start_time).format("YYYY-MM-DD[T]HH:mm:ss"),
                    end_time: moment(end_time).format("YYYY-MM-DD[T]HH:mm:ss")
                }

            },
            getPricePerExtraHour(percentage){
                // obtener el precio por hora, incluido el % agregado al ser hora extra --- 30d * 8h = 240
                return (parseFloat(this.form.accrued.total_base_salary) / 240) * (1 + percentage / 100)
            },
            getTypeOvertimeSurcharge(type)
            {
                return _.find(this.type_overtime_surcharges, {type : type})
            },
            clickAddExtraHour(array_hours, type){

                const type_overtime_surcharge = this.getTypeOvertimeSurcharge(type)
                // console.log(type_overtime_surcharge)

                let percentage_id = type_overtime_surcharge.id
                let quantity = 1
                let price_per_hour = this.getPricePerExtraHour(type_overtime_surcharge.percentage)

                let payment = _.round(price_per_hour * quantity, 2)

                const start_end_date = moment().format("YYYY-MM-DD")
                const start_time = moment().format("HH:mm")
                const end_time = moment().add(quantity, 'hours').format("HH:mm")
                const obj_start_end_time = this.getStartEndTimeFormatApi(start_end_date, start_time, end_time)

                array_hours.push({
                    start_end_date: start_end_date,
                    start_end_time: [
                        start_time,
                        end_time
                    ],
                    quantity :  quantity,
                    percentage :  percentage_id,
                    payment :  payment,
                    start_time: obj_start_end_time.start_time,
                    end_time: obj_start_end_time.end_time,


                    // datos nulos para probar form request
                    // start_end_date: start_end_date,
                    // start_end_time: [
                    //     start_time,
                    //     end_time
                    // ],
                    // quantity :  undefined,
                    // percentage :  undefined,
                    // payment :  undefined,
                    // start_time: null,
                    // end_time: null,
                })

                // this.sumTotalsExtraHours()

            },
            clickCancelExtraHour(array_hours, index){
                array_hours.splice(index, 1)
            },
            create() { 

            }, 
            clickClose() {
                
                // sumar todos los pagos de las horas extras
                this.sumTotalsExtraHours()
                // emitir evento para que se sumen al total devengados en el form principal
                this.$emit('sumTotalsExtraHoursForm')

                this.$emit('update:showDialog', false)

            },
            // getIdTypeOvertimeSurchargeFromType(type){

            //     let id = null

            //     switch (type) {
            //         case 'heds':
            //             id = 1
            //             break;
                
            //         case 'hens':
            //             id = 2
            //             break;

            //         default:
            //             break;
            //     }

            //     return id

            // },
        }
    }
</script>
