<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronRight } from '@lucide/vue';
import { Collapsible, CollapsibleContent, CollapsibleTrigger } from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupLabel,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
} from '@/components/ui/sidebar';
import { useCurrentUrl } from '@/composables/useCurrentUrl';
import type { NavItem } from '@/types';

defineProps<{
    items: NavItem[];
}>();

const { isCurrentUrl } = useCurrentUrl();
</script>

<template>
    <SidebarGroup class="px-2 py-0">
        <SidebarGroupLabel>Plataforma</SidebarGroupLabel>
        <SidebarMenu>
            <template v-for="item in items" :key="item.title">

                <!-- ── CASO A: TIENE SUBMENÚS (DESPLEGABLE MULTINIVEL) ── -->
                <Collapsible v-if="item.items && item.items.length" as-child class="group/collapsible">
                    <SidebarMenuItem>
                        <CollapsibleTrigger as-child>
                            <SidebarMenuButton :tooltip="item.title" class="cursor-pointer"
                                :is-active="item.items.some(sub => isCurrentUrl(sub.href))">
                                <component :is="item.icon" v-if="item.icon" class="size-4 shrink-0" />
                                <span class="font-medium text-xs tracking-wide uppercase opacity-85">{{ item.title
                                    }}</span>
                                <ChevronRight
                                    class="ml-auto size-4 transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90 opacity-50" />
                            </SidebarMenuButton>
                        </CollapsibleTrigger>

                        <CollapsibleContent>
                            <SidebarMenuSub>
                                <SidebarMenuSubItem v-for="subItem in item.items" :key="subItem.title">
                                    <SidebarMenuSubButton as-child :is-active="isCurrentUrl(subItem.href)">
                                        <Link :href="subItem.href">
                                            <span class="text-xs font-semibold transition-colors">
                                                {{ subItem.title }}
                                            </span>
                                        </Link>
                                    </SidebarMenuSubButton>
                                </SidebarMenuSubItem>
                            </SidebarMenuSub>
                        </CollapsibleContent>
                    </SidebarMenuItem>
                </Collapsible>

                <!-- ── CASO B: ENLACE PLANO DIRECTO (COMO ANTES) ── -->
                <SidebarMenuItem v-else>
                    <SidebarMenuButton as-child :is-active="item.href ? isCurrentUrl(item.href) : false"
                    :tooltip="item.title">
                        <Link :href="item.href || '#'">
                            <component :is="item.icon" v-if="item.icon" class="size-4 shrink-0" />
                            <span class="font-medium text-xs tracking-wide uppercase opacity-85">{{ item.title }}</span>
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>

            </template>
        </SidebarMenu>
    </SidebarGroup>
</template>