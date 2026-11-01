<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCouponController extends Controller
{
    public function index()
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('admin.coupons.create', ['coupon' => new Coupon()]);
    }

    public function store(Request $request)
    {
        $coupon = Coupon::create($this->validated($request));

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', "Coupon {$coupon->code} was created.");
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', ['coupon' => $coupon]);
    }

    public function update(Request $request, Coupon $coupon)
    {
        $coupon->update($this->validated($request, $coupon));

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', "Coupon {$coupon->code} was updated.");
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);

        $state = $coupon->is_active ? 'turned on' : 'turned off';

        return back()->with('success', "Coupon {$coupon->code} was {$state}.");
    }

    public function destroy(Coupon $coupon)
    {
        $code = $coupon->code;
        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', "Coupon {$code} was deleted.");
    }

    private function validated(Request $request, ?Coupon $coupon = null): array
    {
        // Codes are always saved in capital letters
        $request->merge(['code' => strtoupper(trim((string) $request->input('code')))]);

        $data = $request->validate([
            'code' => [
                'required', 'string', 'max:50', 'regex:/^[A-Z0-9_-]+$/',
                Rule::unique('coupons', 'code')->ignore($coupon?->id),
            ],
            'type'        => ['required', 'in:percent,fixed'],
            'value'       => [
                'required', 'numeric', 'min:0.01',
                $request->input('type') === 'percent' ? 'max:100' : 'max:99999',
            ],
            'min_order'   => ['nullable', 'numeric', 'min:0', 'max:99999'],
            'usage_limit' => ['nullable', 'integer', 'min:1', 'max:1000000'],
            'expires_at'  => ['nullable', 'date'],
        ], [
            'code.regex'  => 'Use only letters, numbers, dashes and underscores (no spaces).',
            'code.unique' => 'That code already exists.',
            'value.max'   => 'That value is too high (a percent discount can be at most 100).',
        ]);

        $data['min_order'] = $data['min_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}