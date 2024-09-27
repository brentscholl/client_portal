const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

module.exports = {
    theme: {
        fontFamily: {
            'mont': ['Montserrat', 'sans-serif'],
            'sans': ['"ClearSans Regular"', 'serif'] // Ensure fonts with spaces have " " surrounding it.
        },

        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            borderRadius: {
                'xl': '1rem',
            },
            colors: {
                primary: {
                    '50': '#E8E8E8',
                    '100': '#C6C6C7',
                    '200': '#A3A3A5',
                    '300': '#5F5F61',
                    '400': '#2E3032',
                    '500': '#1A1A1D',
                    '600': '#17171A',
                    '700': '#101011',
                    '800': '#0C0C0D',
                    '900': '#080809',
                },
                secondary: {
                    '50': '#F3FBFB',
                    '100': '#E7F8F6',
                    '200': '#C3EDE9',
                    '300': '#9EE2DC',
                    '400': '#15D4BF',
                    '500': '#0DB7A7',
                    '600': '#0CA596',
                    '700': '#086E64',
                    '800': '#06524B',
                    '900': '#043732',
                },
                orange: colors.orange,
                amber: colors.amber,
            },
        },
    },
    variants: {
        extend: {
            ringColor: ['hover', 'active'],
            ringWidth: ['hover', 'active'],
            opacity: ['group-hover'],
            display: ['hover', 'group-hover'],
            visibility: ['hover', 'focus', 'group-hover'],
        }
    },
    // purge: {
    //     enable: true,
    //     content: [
    //         './app/**/*.php',
    //         './resources/**/*.html',
    //         './resources/**/*.js',
    //         './resources/**/*.jsx',
    //         './resources/**/*.ts',
    //         './resources/**/*.tsx',
    //         './resources/**/*.php',
    //         './resources/**/*.vue',
    //         './resources/**/*.twig',
    //     ],
    //     // options: {
    //     //     defaultExtractor: (content) => content.match(/[\w-/.:]+(?<!:)/g) || [],
    //     //     whitelistPatterns: [/-active$/, /-enter$/, /-leave-to$/, /show$/],
    //     // },
    // },
    plugins: [
        // require('@tailwindcss/ui')({
        //     layout: 'sidebar'
        // }),
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
