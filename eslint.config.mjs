import react from "eslint-plugin-react";
import typescriptEslint from "@typescript-eslint/eslint-plugin";
import globals from "globals";
import tsParser from "@typescript-eslint/parser";
import path from "node:path";
import { fileURLToPath } from "node:url";
import js from "@eslint/js";
import {FlatCompat} from "@eslint/eslintrc";
import neostandard from 'neostandard';
import stylisticTs from '@stylistic/eslint-plugin-ts';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const compat = new FlatCompat({
    baseDirectory: __dirname,
    recommendedConfig: js.configs.recommended,
    allConfig: js.configs.all
});

export default [ ...neostandard(), ...compat.extends(
    "plugin:react/recommended",
    "plugin:@typescript-eslint/recommended",
), {
    plugins: {
        react,
        "@typescript-eslint": typescriptEslint,
        '@stylistic/ts': stylisticTs,
    },
    languageOptions: {
        globals: {
            ...globals.browser,
        },
        parser: tsParser,
        ecmaVersion: "latest",
        sourceType: "module",

        parserOptions: {
            project: ["./tsconfig.json"],
        },
    },

    settings: {
        react: {
            version: "detect",
        },
    },

    rules: {
        "react/react-in-jsx-scope": "off",
        "no-undef": "off",
        "react/prop-types": "off",
        semi: ["error", "always"],

        "space-before-function-paren": ["error", {
            anonymous: "always",
            named: "never",
            asyncArrow: "always",
        }],

        "comma-dangle": ["error", {
            arrays: "always-multiline",
            objects: "always-multiline",
            imports: "always-multiline",
            exports: "always-multiline",
            functions: "never",
        }],

        "no-void": "off",

        "@typescript-eslint/no-floating-promises": ["error", {
            ignoreVoid: true,
        }],

        "@stylistic/semi": ["error", "always"],

        "@stylistic/indent": ["error", 4],

        "@stylistic/member-delimiter-style": ["error", {
            multiline: {
                delimiter: "semi",
                requireLast: true,
            },

            singleline: {
                delimiter: "semi",
                requireLast: true,
            },

            multilineDetection: "brackets",
        }],

        "@stylistic/type-annotation-spacing": ["error", {
            before: true,
            after: true,

            overrides: {
                colon: {
                    before: false,
                    after: true,
                },
            },
        }],

        "@stylistic/jsx-tag-spacing": ["error", {
            "closingSlash": "never",
            "beforeSelfClosing": "never",
            "afterOpening": "never",
            "beforeClosing": "allow"
        }],

        "@stylistic/jsx-indent": ["error", 4],

        "@stylistic/jsx-indent-props": ["error", 4],

        "@stylistic/jsx-quotes": ["error", "prefer-double"],

        "@stylistic/space-before-function-paren": ["error", {
            "anonymous": "always",
            "named": "never",
            "asyncArrow": "always"
        }],

        "@stylistic/object-curly-spacing": ["off", "never"],

        "@stylistic/ts/object-curly-spacing": ["off", 'always'],

        "@stylistic/jsx-curly-newline": ['error', {
            multiline: 'consistent',
            singleline: 'consistent'
        }],

        "@typescript-eslint/consistent-type-definitions": ["error", "type"],
    },
}];
