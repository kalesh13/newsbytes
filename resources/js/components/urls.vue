<template lang="pug">
section
    .d-flex.mb-3.align-items-center
        h6.mb-0 Url History
        .ms-auto.col-md-6
            input.form-control(
                v-model='search',
                placeholder='Enter original url to search',
                @keypress.enter='triggerSearch'
            )
    .w-100.overflow-auto
        table.table.table-striped.table-hover.small
            thead
                tr
                    th(scope='col') Tiny url
                    th.text-center(scope='col') Restricted
                    th.text-center(scope='col') Clicks
                    th(scope='col') Original url
            tbody
                tr(v-if='urls.length == 0')
                    td.text-center(colspan='4') No urls found.
                tr(v-for='url in urls', :key='url.id')
                    td {{ url.tiny_url }}
                    td.text-center 
                        span.fas.fa-check.text-success(v-if='url.access_only_once')
                        span.fas.fa-times.text-danger(v-else)
                    td.text-center {{ url.opens }}
                    td {{ url.original_url }}
</template>

<script>
export default {
    name: 'UrlHistories',
    mounted: function () {
        this.fetchHistory();
    },
    beforeDestroy: function () {
        this.clearTimers();
    },
    data: function () {
        return {
            urls: [],
            search: null,
            pollTimer: null,
        };
    },
    methods: {
        clearTimers: function () {
            clearTimeout(this.pollTimer);
        },
        triggerSearch: function () {
            this.clearTimers();
            this.fetchHistory();
        },
        fetchHistory: function () {
            if (this.search === '') {
                this.search = null;
            }

            axios
                .get('/api/urls', { params: { search: this.search } })
                .then(this.onResultsFetched)
                .catch(this.onError)
                .finally(() => {
                    if (null != this.search) {
                        return;
                    }
                    this.pollTimer = setTimeout(this.fetchHistory.bind(this), 10000);
                });
        },
        onResultsFetched: function (response) {
            this.urls = response.data;
        },
        onError: function (err) {
            console.log(err);
        },
    },
};
</script>