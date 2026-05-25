<?php
final class Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public static function make(array $config): self
    {
        return new self(Db::pdo($config));
    }

    /** @return array<int, array<string, mixed>> */
    public function services(): array
    {
        return $this->pdo->query('SELECT title, summary, icon, sort_order FROM v_active_services ORDER BY sort_order ASC')->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function officers(): array
    {
        return $this->pdo->query('SELECT name, title, role_code, message, sort_order FROM officers ORDER BY sort_order ASC')->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function branches(): array
    {
        return $this->pdo->query('SELECT area, name, description, sort_order FROM branches ORDER BY sort_order ASC')->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function histories(): array
    {
        return $this->pdo->query('SELECT year_label, title, body, sort_order FROM histories ORDER BY sort_order ASC')->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function works(): array
    {
        return $this->pdo->query('SELECT title, category, location, summary, sort_order FROM works ORDER BY sort_order ASC')->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function kpis(): array
    {
        return $this->pdo->query('SELECT label, value, unit, memo, sort_order FROM kpis ORDER BY sort_order ASC')->fetchAll();
    }

    /** @return array<int, array<string, mixed>> */
    public function serviceShare(): array
    {
        return $this->pdo->query('SELECT label, percent FROM service_share ORDER BY sort_order ASC')->fetchAll();
    }
}
