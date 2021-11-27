<template lang="pug">
.container-fluid
    .content.d-flex.mx-auto.flex-column.py-5
        url-shortner-form.mb-5(@submitting='reset', @created='onCreate')
        .alert.alert-success.small.text-center.mb-5(v-if='show_alert')
            .title.pb-2
                span.far.fa-thumbs-up.me-3
                | Tiny url created
            .big.bold.mt-4 {{ tiny_url }}
        url-history.mt-5
</template>

<script>
import UrlHistory from '../components/urls';
import UrlShortnerForm from '../components/shortner';

export default {
    name: 'HomePage',
    data: function () {
        return {
            tiny_url: '',
            show_alert: false,
        };
    },
    methods: {
        reset: function () {
            this.tiny_url = '';
            this.show_alert = false;
        },
        onCreate: function (url_mapping) {
            this.tiny_url = url_mapping.tiny_url;
            this.show_alert = true;
        },
    },
    components: { UrlHistory, UrlShortnerForm },
};
</script>