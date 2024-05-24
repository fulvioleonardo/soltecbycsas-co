<v-snackbar v-model="notification" :bottom="y === 'bottom'" :left="x === 'left'" :multi-line="mode === 'multi-line'" :right="x === 'right'" :timeout="timeout" :top="y === 'top'" :vertical="mode === 'vertical'" :color="color">
    @{{notificationText}}
    <v-btn flat @click="closeSnackbarNotification()">Cerrar</v-btn>
</v-snackbar>