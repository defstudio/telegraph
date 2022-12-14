import theme from '@nuxt/content-theme-docs'

export default theme({
    docs: {
        primaryColor: '#1093ff'
    },
    target: 'static',
    content: {
        liveEdit: false
    },
    router: {
        base: '/telegraph'
    },
    buildModules: [
        ['@nuxtjs/google-analytics', {id: 'UA-211287441-1'}]
    ],
    build: {
        optimization: {
            splitChunks: {
                name: true
            }
        }
    }
});
