export default defineAppConfig({
  docus: {
    title: 'Telegraph',
    description: 'Telegram bots made easy',
      url: '/telegraph',
    image: '/logo-dark.png',
    socials: {
      twitter: 'FabioIvona',
      github: 'defstudio/telegraph',
    },
    layout: 'docs',
    aside: {
      level: 0,
      collapsed: false,
      exclude: []
    },
    main: {
      padded: true,
      fluid: false
    },
    header: {
      logo: false,
      exclude: ['/telegraph'],
      fluid: false
    }
  }
})
