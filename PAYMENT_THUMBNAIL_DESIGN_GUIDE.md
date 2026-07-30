# Payment Thumbnail Design Guide

## 🎨 Visual Design Specifications

### Design System Overview

Sistem thumbnail pembayaran menggunakan pendekatan **visual-first** untuk memudahkan admin mengidentifikasi jenis transaksi dengan cepat.

---

## 1️⃣ CASH PAYMENT THUMBNAIL

### Specifications:

```
┌─────────────────────────────────────────────────────────────┐
│  CASH PAYMENT (OFFLINE) - Rp 500.000                        │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│   ╔═══════════════════╗                                     │
│   ║                   ║                                     │
│   ║   ┌─────────┐     ║   Penuh                             │
│   ║   │ 💰      │     ║   🪙 Tunai (Offline)               │
│   ║   │ WALLET  │     ║   📅 29 Jul 2026 14:30             │
│   ║   │  ICON   │     ║                                     │
│   ║   └─────────┘     ║   Rp 500.000                        │
│   ║                   ║   ✅ Terverifikasi                  │
│   ║  GREEN GRADIENT   ║                                     │
│   ╚═══════════════════╝                                     │
│   64x64px                                                    │
│   Border: light green                                        │
│   Shadow: subtle                                             │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### CSS Classes:
```css
Container: w-16 h-16 rounded-xl shadow-sm border border-green-200
Background: bg-gradient-to-br from-green-100 to-emerald-100
Icon: w-8 h-8 text-green-600
Hover: No interaction (static display)
```

### Color Values:
- Background Start: `#f0fdf4` (green-100)
- Background End: `#d1fae5` (emerald-100)
- Border: `#bbf7d0` (green-200)
- Icon: `#16a34a` (green-600)

---

## 2️⃣ ONLINE PAYMENT WITH PROOF

### Specifications:

```
┌─────────────────────────────────────────────────────────────┐
│  ONLINE PAYMENT (QRIS/BCA) - DP Rp 250.000                  │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│   ╔═══════════════════╗                                     │
│   ║                   ║                                     │
│   ║   [PROOF IMAGE]   ║   Uang Muka (DP)                    │
│   ║   Bukti Transfer  ║   QRIS / BCA                        │
│   ║   Screenshot      ║   📅 28 Jul 2026 10:15             │
│   ║   (Clickable)     ║                                     │
│   ║                   ║   Rp 250.000                        │
│   ║   HOVER: RING     ║   ✅ Terverifikasi                  │
│   ╚═══════════════════╝                                     │
│   64x64px                                                    │
│   Border: gray → green (on hover)                            │
│   Cursor: pointer                                            │
│   Action: Open in new tab                                    │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### CSS Classes:
```css
Container: w-16 h-16 rounded-xl object-cover border border-gray-200
Hover: border-[#2F4538] opacity-80 cursor-pointer
Transition: all 200ms ease
Click: window.open(imageUrl, '_blank')
```

### Color Values:
- Border Default: `#e5e7eb` (gray-200)
- Border Hover: `#2F4538` (brand green)
- Hover Opacity: `80%`

### Interaction States:
- **Default**: Gray border, 100% opacity
- **Hover**: Green border, 80% opacity, cursor pointer
- **Click**: Opens image in new browser tab

---

## 3️⃣ ONLINE PAYMENT WITHOUT PROOF

### Specifications:

```
┌─────────────────────────────────────────────────────────────┐
│  ONLINE PAYMENT (PENDING) - Menunggu Upload                 │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│   ╔═══════════════════╗                                     │
│   ║                   ║                                     │
│   ║   ┌─────────┐     ║   Uang Muka (DP)                    │
│   ║   │ 💳      │     ║   QRIS                              │
│   ║   │  CARD   │     ║   📅 30 Jul 2026 08:00             │
│   ║   │  ICON   │     ║                                     │
│   ║   └─────────┘     ║   Rp 250.000                        │
│   ║                   ║   ⏳ Menunggu                       │
│   ║    GRAY BOX       ║                                     │
│   ╚═══════════════════╝                                     │
│   64x64px                                                    │
│   Border: medium gray                                        │
│   No interaction                                             │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### CSS Classes:
```css
Container: w-16 h-16 rounded-xl bg-gray-200 border border-gray-300
Icon: w-7 h-7 text-gray-400
Flex: flex items-center justify-center
Hover: No interaction
```

### Color Values:
- Background: `#e5e7eb` (gray-200)
- Border: `#d1d5db` (gray-300)
- Icon: `#9ca3af` (gray-400)

---

## 📐 Layout Architecture

### Card Structure:

```html
<div class="card-container">
  ┌─ Thumbnail (64x64)
  │   └─ Conditional rendering based on method
  │
  └─ Payment Info (flex-1)
      ├─ Title + Status Badge
      │   ├─ Type: "Uang Muka (DP)" / "Penuh"
      │   └─ Badge: Terverifikasi/Menunggu/Ditolak
      │
      ├─ Method & Date
      │   ├─ Method: CASH / QRIS / BCA
      │   └─ Date: "29 Jul 2026 14:30"
      │
      └─ Amount (Bold)
          └─ Rp 250.000 / Rp 500.000
</div>
```

### Spacing & Alignment:

```
┌──────────────────────────────────────────────────────────────┐
│ [Thumbnail]  [Info Content]                                  │
│    64px      ← gap-3 (12px) →   Flex-1                       │
│   Fixed                         Flexible width                │
│   Shrink-0                      Min-width-0 (text ellipsis)  │
└──────────────────────────────────────────────────────────────┘
```

---

## 🎯 Design Principles

### 1. **Visual Hierarchy**
- Thumbnail size: 64x64px (consistent across all types)
- Amount: Bold, large font (most important)
- Status: Color-coded badge (amber/green/red)
- Date: Small, gray (least important)

### 2. **Color Coding System**

| Payment Type | Thumbnail Color | Meaning |
|-------------|----------------|---------|
| Cash (Offline) | 🟢 Green Pastel | Success, Verified, Physical |
| Online with Proof | 🖼️ Image Border | Evidence Available, Clickable |
| Online no Proof | ⚪ Gray | Pending, Incomplete |

### 3. **Interaction Patterns**

| Thumbnail Type | Hover | Click | Cursor |
|---------------|-------|-------|--------|
| Cash | No effect | No action | Default |
| Online + Proof | Border green | Open image | Pointer |
| Online - Proof | No effect | No action | Default |

---

## 🔍 Icon Library

### Wallet Icon (Cash):
```svg
<svg viewBox="0 0 24 24">
  <path d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
</svg>
```

### Credit Card Icon (No Proof):
```svg
<svg viewBox="0 0 24 24">
  <path d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
</svg>
```

### Money Icon (Label):
```svg
<svg viewBox="0 0 24 24">
  <path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
</svg>
```

---

## 📊 Comparison Table

| Aspect | Cash Payment | Online + Proof | Online - Proof |
|--------|-------------|----------------|----------------|
| **Thumbnail** | Green gradient box | Proof image | Gray box |
| **Icon** | 💰 Wallet | No icon (image) | 💳 Card |
| **Size** | 64x64px | 64x64px | 64x64px |
| **Border** | Green-200 | Gray-200 → Green on hover | Gray-300 |
| **Background** | Gradient pastel | Image | Solid gray |
| **Shadow** | Subtle | None | None |
| **Clickable** | ❌ No | ✅ Yes | ❌ No |
| **Hover Effect** | ❌ No | ✅ Border + opacity | ❌ No |
| **Cursor** | Default | Pointer | Default |

---

## 🎨 Brand Color Integration

### Primary Brand Colors:
- **Primary Green**: `#2F4538`
  - Used for: Amount text, hover borders
- **Secondary Green**: `#3d5a49`
  - Used for: Gradients, accents

### Semantic Colors:
- **Success/Verified**: Green (`#16a34a`)
- **Warning/Pending**: Amber (`#f59e0b`)
- **Error/Rejected**: Red (`#dc2626`)

---

## 📱 Responsive Behavior

### All Breakpoints:
- Thumbnail: Fixed 64x64px (no resize)
- Layout: Horizontal flex (thumbnail + info)
- Info section: Flexible, min-width-0 for text truncation
- Spacing: gap-3 (12px) consistent

### Mobile Optimization:
- Card padding: p-3 (12px all sides)
- Info text: Ellipsis on overflow
- Status badge: Absolute positioned on small screens
- Touch target: 44x44px minimum (thumbnail exceeds this)

---

## ✅ Accessibility Considerations

1. **Alt Text**: Images have descriptive alt="Bukti Transfer"
2. **Color Contrast**: All text meets WCAG AA standards
3. **Interactive Elements**: Only clickable items have cursor:pointer
4. **Visual Cues**: Icon + text for payment method (not color alone)
5. **Touch Targets**: 64x64px exceeds 44x44px minimum

---

## 🧪 Testing Checklist

### Visual Testing:
- [ ] Cash thumbnail shows green gradient
- [ ] Cash icon centered and visible
- [ ] Online proof shows actual image
- [ ] Online proof hover changes border to green
- [ ] No proof shows gray box with card icon
- [ ] All thumbnails same size (64x64)
- [ ] Borders consistent thickness
- [ ] Gradients smooth transition

### Interaction Testing:
- [ ] Cash thumbnail not clickable
- [ ] Proof image opens in new tab on click
- [ ] Proof image hover shows visual feedback
- [ ] No proof thumbnail not clickable
- [ ] Cursor changes only on proof images

### Data Testing:
- [ ] method === 'CASH' shows green box
- [ ] method !== 'CASH' + proofUrl shows image
- [ ] method !== 'CASH' + !proofUrl shows gray box
- [ ] All payment types display correctly

---

## 📝 Implementation Notes

- Uses Alpine.js for conditional rendering
- No external image libraries required
- SVG icons inline for performance
- Gradient uses Tailwind built-in classes
- Hover states use CSS transitions
- Click handler uses vanilla JS window.open()

---

## 📅 Version History

- **v1.0** (2026-07-30): Initial design implementation
  - Cash payment green gradient thumbnail
  - Online payment proof image thumbnail
  - Fallback gray box for missing proofs

---

## 🚀 Future Design Enhancements

1. **Animated Icons**: Subtle bounce on hover
2. **Payment Method Icons**: Unique icons for QRIS vs BCA
3. **Skeleton Loaders**: While images loading
4. **Dark Mode**: Alternative color palette
5. **Custom Illustrations**: Replace generic icons with custom artwork

---

**Design System**: Kos Putri Gardenia  
**Component**: Payment History Thumbnail  
**Last Updated**: 2026-07-30  
**Designer**: Kiro AI Assistant
