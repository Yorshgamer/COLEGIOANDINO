import type { InertiaLinkProps } from '@inertiajs/vue3';
import type { LucideIcon } from '@lucide/vue';
import type { Component } from 'vue';

export type BreadcrumbItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
};

export type NavItem = {
    title: string;
    href?: InertiaLinkProps['href'];
    icon?: Component;
    isActive?: boolean;
    items?: {
        title: string;
        href: string;
    }[];
};
