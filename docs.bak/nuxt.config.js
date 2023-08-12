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
    build: {
        optimization: {
            splitChunks: {
                name: true
            }
        }
    }
});
