import md5 from "md5";
import fs from "fs-extra";
import alias from "@rollup/plugin-alias";
import outputManifest from "rollup-plugin-output-manifest";
import { nodeResolve } from "@rollup/plugin-node-resolve";
import commonjs from "@rollup/plugin-commonjs";

export default {
    input: "js/index.js",
    output: {
        format: "iife",
        sourcemap: false,
        name: "LivewireSpa",
        file: "dist/livewire-spa.js",
    },
    plugins: [
        alias({
            entries: [{ find: "@", replacement: __dirname + "/js" }],
        }),
        outputManifest({
            serialize() {
                const file = fs.readFileSync(
                    __dirname + "/dist/livewire-spa.js",
                    "utf8"
                );
                const hash = md5(file).substr(0, 20);

                return JSON.stringify({
                    "/livewire-spa.js": "/livewire-spa.js?id=" + hash,
                });
            },
        }),
        nodeResolve(),
        commonjs(),
    ],
};
