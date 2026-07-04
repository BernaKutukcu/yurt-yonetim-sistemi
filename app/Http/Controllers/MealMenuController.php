<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MealMenu;

class MealMenuController extends Controller
{
    // Menüleri listele
    public function index()
    {
        $month = request('month', now()->format('Y-m'));
        $year  = substr($month, 0, 4);
        $mon   = substr($month, 5, 2);

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $mon, $year);
        $days = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $year . '-' . $mon . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $existing = MealMenu::where('date', $date)->first();
            $days[] = [
                'date'      => $date,
                'breakfast' => $existing ? $existing->breakfast : '',
                'dinner'    => $existing ? $existing->dinner : '',
                'is_served' => $existing ? $existing->is_served : false,
            ];
        }

        $prevMonth = \Carbon\Carbon::parse($month . '-01')->subMonth()->format('Y-m');
        $nextMonth = \Carbon\Carbon::parse($month . '-01')->addMonth()->format('Y-m');

        return view('admin.meals.index', compact('days', 'month', 'prevMonth', 'nextMonth'));
    }

    // Menü ekleme formu
    public function create()
    {
        return view('admin.meals.create');
    }

    // Menü kaydet
    public function store(Request $request)
    {
        $request->validate([
            'date'      => 'required|date',
            'breakfast' => 'required|string|max:500',
            'dinner'    => 'required|string|max:500',
        ]);

        // Aynı tarihe tekrar eklersen üzerine yazar
        MealMenu::updateOrCreate(
            ['date' => $request->date],
            [
                'breakfast' => $request->breakfast,
                'dinner'    => $request->dinner,
            ]
        );

        return redirect('/admin/meals')->with('success', 'Menü kaydedildi.');
    }

    // Menü sil
    public function destroy($id)
    {
        MealMenu::findOrFail($id)->delete();
        return redirect('/admin/meals')->with('success', 'Menü silindi.');
    }

    // Aylık menü formu
    public function monthly()
    {
        $month = request('month', now()->format('Y-m'));
        $year  = substr($month, 0, 4);
        $mon   = substr($month, 5, 2);

        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $mon, $year);
        $days = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = $year . '-' . $mon . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
            $existing = MealMenu::where('date', $date)->first();
            $days[] = [
                'date'      => $date,
                'breakfast' => $existing ? $existing->breakfast : '',
                'dinner'    => $existing ? $existing->dinner : '',
            ];
        }

        return view('admin.meals.monthly', compact('days', 'month'));
    }

    // Aylık menü kaydet
    public function monthlyStore(Request $request)
    {
        // Modal'dan tekil kayıt
        if ($request->has('modal_date')) {
            MealMenu::updateOrCreate(
                ['date' => $request->modal_date],
                [
                    'breakfast' => $request->modal_breakfast ?? '',
                    'dinner'    => $request->modal_dinner ?? '',
                ]
            );
            $tab = $request->input('modal_tab', 'breakfast');
            return redirect('/admin/meals?month=' . substr($request->modal_date, 0, 7) . '&tab=' . $tab)->with('success', 'Menü kaydedildi.');
        }

        // Aylık toplu kayıt
        $menus = $request->input('menus', []);
        foreach ($menus as $date => $menu) {
            if (!empty($menu['breakfast']) || !empty($menu['dinner'])) {
                MealMenu::updateOrCreate(
                    ['date' => $date],
                    [
                        'breakfast' => $menu['breakfast'] ?? '',
                        'dinner'    => $menu['dinner'] ?? '',
                    ]
                );
            }
        }

        return redirect('/admin/meals')->with('success', 'Menü kaydedildi.');
    }
}
