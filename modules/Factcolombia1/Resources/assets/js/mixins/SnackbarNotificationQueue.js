export default {
    props: {
        show: {
            require: true,
        },
        text: {
            required: false,
            default: null
        },
        y: {
            require: false,
            default: 'top'
        },
        x: {
            require: false,
            default: 'right'
        },
        mode: {
            require: false,
            default: 'vertical'
        },
        timeout: {
            require: false,
            default: 6000
        }
    },
    mounted() {
        this.$root.$on('addSnackbarNotification', ({text, color}) => {
            this.addNotification(text);
            this.color = color;
        });
    },
    data: {
        notificationText: '',
        notificationQueue: [],
        notification: false,
        color: null
    },
    computed: {
        hasNotificationsPending() {
            return this.notificationQueue.length > 0;
        }
    },
    watch: {
        notification() {
            if (!this.notification && this.hasNotificationsPending) {
                this.notificationText = this.notificationQueue.shift();
                this.$nextTick(() => this.notification = true);
            }
        }
    },
    methods: {
        addNotification(text) {
            this.notificationQueue.push(text);
            
            if (!this.notification) {
                this.notificationText = this.notificationQueue.shift();
                this.notification = true;
            }
        },
        closeSnackbarNotification() {
            this.notification = false;
        }
    }
}
