<template>
  <main class="content">
    <div class="container-fluid">
      <v-app id="companies">
        <div class="content-header">
          <h1>Listado de Documentos</h1>
        </div>
        <v-card>
          <v-card-title>
            <v-spacer></v-spacer>
            <v-text-field
              v-model="start"
              type="date"
              label="Desde"
              name="FechaDesde"
              single-line
              hide-details
              placeholder="Fecha Desde"
            ></v-text-field>
            <v-text-field
              v-model="end"
              type="date"
              label="Hasta"
              name="FechaHasta"
              single-line
              hide-details
              placeholder="Fecha Hasta"
            ></v-text-field>
            <v-text-field
              v-model="search"
              label="Buscar"
              single-line
              hide-details
              placeholder="Ingrese NIT"
            ></v-text-field>
            <v-btn
              :loading="loadDataTable"
              @click="searchData"
              color="bee darker text-white no-decoration"
            >Buscar Documentos</v-btn>
          </v-card-title>

          <v-container>
            <h3>{{company}}</h3>
            <table class="v-table v-datatable elevation-5 theme--light" hide-actions>
              <thead>
                <tr>
                  <th class="text-center">Tipo Documento</th>
                  <th class="text-center">Contador</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item, index) in desserts" :key="index">
                  <td class="text-center">{{item.name}}</td>
                  <td class="text-center">{{item.count}}</td>
                </tr>
              </tbody>
            </table>
          </v-container>
        </v-card>
      </v-app>
    </div>
  </main>
</template>

<script>
import { resolve } from "q";
export default {
  props: {
    route: {
      required: true
    }
  },
  data: () => ({
    company: "",
    loadDataTable: false,
    search: "",

    desserts: []
  }),
  filters: {},
  mounted() {},
  created() {},
  methods: {
    searchData() {
      this.search.trim();
      if (this.search < 8) {
        return false;
      }

      this.loadDataTable = true;

      axios
        .get(`${this.route}/informationDocument/${this.search}/${this.start}/${this.end}`)
        .then(response => {
          this.$setLaravelMessage(response.data);

          this.desserts = response.data.data;
          this.company = response.data.companie;
        })
        .catch(error => {
          this.$setLaravelErrors(error.response.data);
        })
        .then(() => {
          this.loadDataTable = false;
        });
    }
  }
};
</script>
