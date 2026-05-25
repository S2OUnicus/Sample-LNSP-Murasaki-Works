<?php
function e(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function asset(string $path): string
{
    return './' . ltrim($path, '/');
}

function tel_href(string $tel): string
{
    return 'tel:' . preg_replace('/[^0-9+]/', '', $tel);
}

function page_title(array $config, string $pageName = '', ?string $category = null): string
{
    $siteName = $config['site']['name'] ?? 'サイト';
    $pageName = $pageName ?: ($config['ui']['default_page_name'] ?? 'トップ');
    $category = $category ?? ($config['ui']['default_page_category'] ?? '');
    $left = $category !== '' ? $pageName . '｜' . $category : $pageName;
    return $left . ' - ' . $siteName;
}

function current_year(): string
{
    return date('Y');
}
