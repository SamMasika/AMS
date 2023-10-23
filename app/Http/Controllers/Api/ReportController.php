<?php

namespace App\Http\Controllers\Api;

use App\Models\Asset;
use App\Models\Electronic;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function assetsBySection($sectionName)
    {
        $assetsBySectionAndCategory = Asset::where('status', 1) // Assuming 1 represents an assigned status
            ->whereHas('section', function ($query) use ($sectionName) {
                $query->where('name', $sectionName);
            })
            ->with(['section', 'staff', 'office', 'electronic', 'furniture']) // Adjust relationships based on your models
            ->get();
            $subcategoriesCount = [];
       
            $subcategoriesCount['Computer'] = $assetsBySectionAndCategory
            ->where('subcategory', 'Computer')
            ->count();
            $subcategoriesCount['Phone'] = $assetsBySectionAndCategory
                ->where('subcategory', 'Phone')
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->phone_type === 'MobilePhone';
                })
                ->count();
            $subcategoriesCount['Television'] = $assetsBySectionAndCategory
                ->where('subcategory', 'Television')
                ->count();
    
            $subcategoriesCount['Disk'] = $assetsBySectionAndCategory
                ->where('subcategory', 'Disk')
                ->count();
    
            $subcategoriesCount['Laptop'] = $assetsBySectionAndCategory
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->computer_type === 'Laptop';
                })
                ->count();
    
            $subcategoriesCount['Desktop'] = $assetsBySectionAndCategory
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->computer_type === 'Desktop';
                })
                ->count();
                $subcategoriesCount['Landline'] = $assetsBySectionAndCategory
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->phone_type === 'Landline';
                })
                ->count();
                $subcategoriesCount['Chair'] = $assetsBySectionAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Chair';
                })
                ->count();
                $subcategoriesCount['Table'] = $assetsBySectionAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Table';
                })
                ->count();
                $subcategoriesCount['Locker'] = $assetsBySectionAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Locker';
                })
                ->count();
       
        return response()->json([
            'success' => true,
            'status' => 300,
            'data' => $assetsBySectionAndCategory,
            'subcategoriesCount' => $subcategoriesCount,
        ], 200);
    
        return response()->json([
            'success' => true,
            'status' => 300,
            'data' => $assetsBySectionAndCategory,
            'count' => $assetsBySectionAndCategorycount,
        ], 200);
    }
    public function assetsByStaff($staffName)
    {
        $assetsByStaffAndCategory = Asset::where('status', 1)
            ->whereHas('staff', function ($query) use ($staffName) {
                $query->where('lname', $staffName);
            })
            ->with(['section', 'staff', 'office', 'electronic', 'furniture'])
            ->get();
    
        $subcategoriesCount = [];

            $subcategoriesCount['Computer'] = $assetsByStaffAndCategory
            ->where('subcategory', 'Computer')
            ->count();
    
            $subcategoriesCount['Phone'] = $assetsByStaffAndCategory
                ->where('subcategory', 'Phone')
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->phone_type === 'MobilePhone';
                })
                ->count();
    
            $subcategoriesCount['Television'] = $assetsByStaffAndCategory
                ->where('subcategory', 'Television')
                ->count();
    
            $subcategoriesCount['Disk'] = $assetsByStaffAndCategory
                ->where('subcategory', 'Disk')
                ->count();
    
            $subcategoriesCount['Laptop'] = $assetsByStaffAndCategory
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->computer_type === 'Laptop';
                })
                ->count();
    
            $subcategoriesCount['Desktop'] = $assetsByStaffAndCategory
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->computer_type === 'Desktop';
                })
                ->count();
                $subcategoriesCount['Chair'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Chair';
                })
                ->count();
                $subcategoriesCount['Table'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Table';
                })
                ->count();
                $subcategoriesCount['Locker'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Locker';
                })
                ->count();
      
    
        return response()->json([
            'success' => true,
            'status' => 300,
            'data' => $assetsByStaffAndCategory,
            'subcategoriesCount' => $subcategoriesCount,
        ], 200);
    }
    public function assetsByOffice($officeName)
    {
        $assetsByOfficeAndCategory = Asset::where('status', 1)
            ->whereHas('office', function ($query) use ($officeName) {
                $query->where('name', $officeName);
            })
            ->with(['section', 'staff', 'office', 'electronic', 'furniture'])
            ->get();
    
        $subcategoriesCount = [];
    
       
            $subcategoriesCount['Computer'] = $assetsByOfficeAndCategory
            ->where('subcategory', 'Computer')
            ->count();
    
            $subcategoriesCount['Phone'] = $assetsByOfficeAndCategory
                ->where('subcategory', 'Phone')
                ->count();
    
            $subcategoriesCount['Television'] = $assetsByOfficeAndCategory
                ->where('subcategory', 'Television')
                ->count();
    
            $subcategoriesCount['Disk'] = $assetsByOfficeAndCategory
                ->where('subcategory', 'Disk')
                ->count();
    
            $subcategoriesCount['Laptop'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->computer_type === 'Laptop';
                })
                ->count();
    
            $subcategoriesCount['Desktop'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->electronic && $asset->electronic->computer_type === 'Desktop';
                })
                ->count();
                $subcategoriesCount['Chair'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Chair';
                })
                ->count();
                $subcategoriesCount['Table'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Table';
                })
                ->count();
                $subcategoriesCount['Locker'] = $assetsByOfficeAndCategory
                ->filter(function ($asset) {
                    return $asset->furniture && $asset->furniture->furniture_type === 'Locker';
                })
                ->count();
      
      
    
        return response()->json([
            'success' => true,
            'status' => 300,
            'data' => $assetsByOfficeAndCategory,
            'subcategoriesCount' => $subcategoriesCount,
        ], 200);
    }
    

    
}
