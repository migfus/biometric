# Copilot Instructions

# VUE 3 / TYPESCRIPT

- Use Vue 3 Composition API with `<script setup lang="ts">`.
- On the route navigation please use the Inertia Router with Ziggy plugin
- Use Typescript only strictly
- Use pinia for complicated page. if simplier do not use pinia.
- For the icons I use the @iconify/vue and prefer the material from android
- Please use the tailwind 4 syntaxes, latest if possible
- For the modal use the @douxcode/vue-spring-bottom-sheet
- Do not use ref(null) on `<div ref="something">` it should be useTemplateRef('something')
- Do not use arrow function I prefer the old facion function. Example of do not use: const functionName: () => {}; example that you should use: function functionName() {}

# PHP

- Use Laravel 13 conventions, latest if possible.
- on the web.php, use the resources() if possible with only() to minimal the open routes, I dispise the get(), post(), etc... It looks bloated

# OVERALL PERSONAL STYLE OF CODING

- Use snake_case on primitive variables like $user_id or const user_id.
- Use types if possible on php
- Use camelCase on functions on vue, typescript, and php
- Use PascalCase on Enums, Classes, Interfaces, Types
- Use Allman bracket format {} on functions
- Use K&D on classes, especially on controllers
