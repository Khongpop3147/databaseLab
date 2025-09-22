<?php

namespace App\Http\Controllers;

use App\Models\SocialMediaLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialMediaLinkController extends Controller
{
    // Display all social media links of the authenticated user
    public function index()
    {
        // ดึงลิงก์โซเชียลทั้งหมดที่เกี่ยวข้องกับผู้ใช้ที่ล็อกอิน
        $links = Auth::user()->socialMediaLinks;

        // ส่งข้อมูล $links ไปที่ view 'social_media_links.index'
        return view('social_media_links.index', compact('links'));
    }

    // Show form to create a new social media link
    public function create()
    {
        return view('social_media_links.create');
    }

    // Store a new social media link
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        // สร้างลิงก์โซเชียลใหม่ให้กับผู้ใช้ที่ล็อกอินอยู่
        Auth::user()->socialMediaLinks()->create($request->all());

        // กลับไปที่หน้ารายการลิงก์โซเชียล
        return redirect()->route('social_media_links.index')->with('status', 'Link added successfully!');
    }

    // Show form to edit an existing social media link
 public function edit(SocialMediaLink $socialMediaLink)
{
    // ส่งข้อมูล link ไปยัง view
    return view('social_media_links.edit', compact('socialMediaLink'));
}


    // Update the specified social media link
    public function update(Request $request, SocialMediaLink $socialMediaLink)
    {
        

        // Validate the incoming request data
        $request->validate([
            'platform' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        // อัปเดตข้อมูลลิงก์
        $socialMediaLink->update($request->all());

        // กลับไปที่หน้ารายการลิงก์โซเชียล
        return redirect()->route('social_media_links.index')->with('status', 'Link updated successfully!');
    }

    // Delete a social media link
    public function destroy(SocialMediaLink $socialMediaLink)
    {
        // ตรวจสอบว่าเป็นเจ้าของลิงก์หรือไม่
        $this->authorize('delete', $socialMediaLink);

        // ลบลิงก์ออกจากฐานข้อมูล
        $socialMediaLink->delete();

        // กลับไปที่หน้ารายการลิงก์โซเชียล
        return redirect()->route('social_media_links.index')->with('status', 'Link deleted successfully!');
    }
}
