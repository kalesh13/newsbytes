<template lang="pug">
form(@submit.prevent='submit')
    .form-group
        label.bold.small.mb-2 Original URL
        input.form-control(v-model='original_url', placeholder='https://www.google.com/')
        .mt-2.x-small.px-1.error(v-if='error') {{ error }}
    .d-flex.align-items-center.mt-3
        input(type='checkbox', v-model='access_only_once')
        label.small.ms-2 Restrict to single use
        button.btn.ms-auto(type='submit', :disabled='posting') 
            span.fas.fa-circle-notch.fa-spin.me-2(v-if='posting')
            | Create tiny-url
</template>

<script>
export default {
    name: 'URLShortnerForm',
    data: function () {
        return {
            original_url: '',
            access_only_once: false,
            error: '',
            posting: false,
        };
    },
    methods: {
        reset: function () {
            this.error = '';
        },
        submit: function () {
            this.reset();

            if (!this.original_url) {
                return (this.error = 'Please enter a url');
            }
            this.$emit('submitting', this.original_url);

            this.posting = true;
            axios
                .post('/api/tiny-url', {
                    original_url: this.original_url,
                    access_only_once: this.access_only_once,
                })
                .then(this.onPostSuccess)
                .catch(this.onPostError)
                .finally(() => (this.posting = false));
        },
        onPostSuccess: function (response) {
            this.$emit('created', response.data);
        },
        onPostError: function (err) {
            this.error = err?.response?.data?.message || 'Error creating tiny url';
        },
    },
};
</script>