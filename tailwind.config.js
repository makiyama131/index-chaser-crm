// [修正] typography を import に変更
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
        // [追加] Laravelのページネーション用スタイルをスキャン対象に追加
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#60a5fa',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                },
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                },
                warning: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                danger: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                },
                neutral: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    200: '#e2e8f0',
                    300: '#cbd5e1',
                    400: '#94a3b8',
                    500: '#64748b',
                    600: '#475569',
                    700: '#334155',
                    800: '#1e293b',
                    900: '#0f172a',
                }
            },
            fontFamily: {
                'sans': ['Inter', 'Noto Sans JP', 'system-ui', 'sans-serif'],
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in-out',
                'slide-up': 'slideUp 0.3s ease-out',
                'bounce-gentle': 'bounceGentle 0.6s ease-in-out',
                'pulse-soft': 'pulseSoft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                bounceGentle: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.05)' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.8' },
                }
            },
            boxShadow: {
                'soft': '0 2px 15px 0 rgba(0, 0, 0, 0.1)',
                'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
            },
            backdropBlur: {
                xs: '2px',
            }
        },
    },
    plugins: [
        // [修正] requireではなくimportした変数を使用
        forms,
        typography,
        function({ addUtilities }) {
            const newUtilities = {
                '.btn-primary': {
                    '@apply bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-900 shadow-sm': {},
                    '&:disabled': {
                        '@apply opacity-25': {}
                    }
                },
                '.btn-secondary': {
                    '@apply bg-white text-neutral-700 border-neutral-300 hover:bg-neutral-50 active:bg-neutral-100 shadow-sm': {},
                    '&:disabled': {
                        '@apply opacity-25': {}
                    }
                },
                '.btn-danger': {
                    '@apply bg-danger-600 text-white hover:bg-danger-700 active:bg-danger-900 shadow-sm': {},
                     '&:disabled': {
                        '@apply opacity-25': {}
                    }
                },
                '.glass': {
                    'background': 'rgba(255, 255, 255, 0.8)',
                    'backdrop-filter': 'blur(10px)',
                    'border': '1px solid rgba(255, 255, 255, 0.2)',
                },
                '.glass-dark': {
                    'background': 'rgba(0, 0, 0, 0.8)',
                    'backdrop-filter': 'blur(10px)',
                    'border': '1px solid rgba(255, 255, 255, 0.1)',
                },
                '.btn-base': {
                    'display': 'inline-flex',
                    'align-items': 'center',
                    'padding': '0.5rem 1rem',
                    'font-weight': '600',
                    'text-align': 'center',
                    'border-radius': '0.375rem',
                    'transition': 'all 0.2s ease-in-out',
                    'text-decoration': 'none',
                    '&:focus': {
                        'outline': '2px solid transparent',
                        'outline-offset': '2px',
                        'box-shadow': '0 0 0 3px rgba(59, 130, 246, 0.5)',
                    }
                }
            }
            addUtilities(newUtilities)
        }
    ],
}
