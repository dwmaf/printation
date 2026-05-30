import "./bootstrap";
import "../css/app.css";

import React from "react";
import { createRoot, Root } from "react-dom/client";
import { createInertiaApp } from "@inertiajs/react";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

interface ImportMetaEnv {
    readonly VITE_APP_NAME?: string;
}

interface ImportMeta {
    readonly env: ImportMetaEnv;
}

const appName: string =
    (import.meta.env as unknown as ImportMetaEnv).VITE_APP_NAME || "Laravel";

createInertiaApp({
    title: (title: string): string => `${title} - ${appName}`,
    resolve: async (name: string) => {
        // Kita gunakan async-await agar proses pencarian komponen lebih stabil
        const page = await resolvePageComponent<React.ComponentType>(
            `./Pages/${name}.tsx`,
            import.meta.glob<React.ComponentType>("./Pages/**/*.tsx"),
        );

        // Proteksi tambahan: jika gagal memuat, beri peringatan yang jelas di konsol
        if (!page) {
            console.error(
                `Komponen halaman "${name}" tidak ditemukan di direktori ./Pages/`,
            );
        }

        return page;
    },
    setup({ el, App, props }) {
        const root: Root = createRoot(el);

        root.render(<App {...props} />);

        // Menghapus loading screen bawaan HTML setelah React siap melakukan render
        const loader: HTMLElement | null =
            document.getElementById("initial-loader");
        if (loader) {
            loader.remove();
        }
    },
    progress: {
        color: "#4B5563",
    },
});
