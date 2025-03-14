<?php
namespace App\Repositories;

use App\Models\Ad;

class AdRepository
{
    public function all($filters = [])
    {
        $query = Ad::query()
            ->with(['category', 'region', 'tags']);

        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (isset($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }
        if (isset($filters['price_min'])) {
            $query->where('price', '>=', $filters['price_min']);
        }
        if (isset($filters['price_max'])) {
            $query->where('price', '<=', $filters['price_max']);
        }
        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('description', 'like', '%' . $filters['search'] . '%');
            });
        }
        if (isset($filters['sort_by']) && isset($filters['sort_dir'])) {
            $query->orderBy($filters['sort_by'], $filters['sort_dir']);
        }

        return $query->paginate(10);
    }

    public function create(array $data)
    {
        return Ad::create($data);
    }

    public function update(Ad $ad, array $data)
    {
        $ad->update($data);
        return $ad;
    }

    public function delete(Ad $ad)
    {
        $ad->delete();
    }

    public function find($id)
    {
        return Ad::with(['category', 'region', 'tags'])->findOrFail($id);
    }
}