<template>
    <v-btn flat>
        <v-badge left>
            <span v-if="pendingNotifications != 0" slot="badge">{{pendingNotifications}}</span>
            <v-icon large color="grey lighten-1">notifications</v-icon>
        </v-badge>
    </v-btn>
</template>

<script>
    export default {
        props: {
            user: {
                required: true
            }
        },
        data: () => ({
            pendingNotifications: 0
        }),
        mounted() {
            Echo.private(`App.User.${this.user.id}`).listen('NotificationUser', data => {
                console.log(data);
            });
        }
    }
</script>
