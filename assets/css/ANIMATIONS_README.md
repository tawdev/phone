# دليل استخدام الأنيميشن - Animations Guide

## نظرة عامة - Overview

تم إنشاء ملف `animations.css` يحتوي على مجموعة شاملة من الأنيميشن الاحترافية والعصرية المصممة خصيصاً لموقع تجارة إلكترونية لبيع الهواتف.

A comprehensive animations CSS file has been created with professional and modern animations designed specifically for an e-commerce phone store.

---

## الأنيميشن المتاحة - Available Animations

### 1. **أنيميشن الظهور - Fade In Animations**

#### `.animate-fade-in`
- تأثير ظهور ناعم مع تكبير بسيط
- Smooth fade in with slight scale

#### `.animate-fade-in-up`
- ظهور من الأسفل إلى الأعلى
- Fade in from bottom to top

#### `.animate-fade-in-down`
- ظهور من الأعلى إلى الأسفل
- Fade in from top to bottom

#### `.animate-fade-in-left`
- ظهور من اليسار
- Fade in from left

#### `.animate-fade-in-right`
- ظهور من اليمين
- Fade in from right

**مثال - Example:**
```html
<div class="animate-fade-in-up">عنصر متحرك</div>
```

---

### 2. **أنيميشن التكبير - Scale Animations**

#### `.animate-scale-in`
- تكبير تدريجي مع ظهور
- Gradual scale with fade in

#### `.animate-bounce-in`
- تأثير ارتداد احترافي
- Professional bounce effect

**مثال - Example:**
```html
<div class="animate-scale-in">بطاقة منتج</div>
```

---

### 3. **أنيميشن الدوران - Rotate Animations**

#### `.animate-rotate-fade`
- دوران مع ظهور
- Rotate with fade in

---

### 4. **أنيميشن مستمرة - Continuous Animations**

#### `.animate-float`
- حركة طفو مستمرة
- Continuous floating motion

#### `.animate-pulse`
- نبض مستمر
- Continuous pulse

#### `.animate-shimmer`
- تأثير بريق
- Shimmer effect

**مثال - Example:**
```html
<div class="animate-float">عنصر يطفو</div>
```

---

### 5. **أنيميشن عند التمرير - Hover Animations**

#### `.hover-lift`
- رفع العنصر عند التمرير
- Lift element on hover

#### `.hover-scale`
- تكبير عند التمرير
- Scale on hover

#### `.hover-glow`
- توهج عند التمرير
- Glow on hover

#### `.hover-rotate`
- دوران عند التمرير
- Rotate on hover

#### `.hover-shine`
- تأثير لمعان
- Shine effect

**مثال - Example:**
```html
<div class="product-card hover-lift">
    <img src="..." class="product-image-zoom">
</div>
```

---

### 6. **أنيميشن البطاقات - Card Animations**

#### `.product-card-animate`
- أنيميشن متدرج للبطاقات
- Staggered animation for cards

#### `.category-card-animate`
- أنيميشن متدرج لبطاقات الفئات
- Staggered animation for category cards

---

### 7. **أنيميشن الأزرار - Button Animations**

#### `.btn-animate`
- تأثير موجة عند النقر
- Ripple effect on click

#### `.btn-ripple`
- تأثير موجة احترافي
- Professional ripple effect

**مثال - Example:**
```html
<a href="#" class="btn btn-primary btn-animate">زر</a>
```

---

### 8. **أنيميشن Hero Section**

#### `.hero-animate`
- أنيميشن للعنوان الرئيسي
- Animation for main title

#### `.hero-animate-delay-1`
- تأخير 0.2 ثانية
- 0.2s delay

#### `.hero-animate-delay-2`
- تأخير 0.4 ثانية
- 0.4s delay

#### `.hero-animate-delay-3`
- تأخير 0.6 ثانية
- 0.6s delay

**مثال - Example:**
```html
<section class="hero">
    <h1 class="hero-animate">عنوان</h1>
    <p class="hero-animate-delay-1">نص</p>
    <a href="#" class="btn hero-animate-delay-2">زر</a>
</section>
```

---

### 9. **أنيميشن متدرجة - Staggered Animations**

#### `.stagger-animate`
- أنيميشن متدرجة للأطفال
- Staggered animation for children

**مثال - Example:**
```html
<div class="products-grid stagger-animate">
    <div class="product-card">...</div>
    <div class="product-card">...</div>
    <div class="product-card">...</div>
</div>
```

---

### 10. **أنيميشن عند التمرير - Scroll Animations**

#### `.scroll-animate`
- أنيميشن عند الظهور في الشاشة
- Animation when appearing on screen

**مثال - Example:**
```html
<div class="scroll-animate">عنصر يظهر عند التمرير</div>
```

---

## الاستخدام في الموقع - Usage in Site

### الصفحة الرئيسية - Homepage

```html
<!-- Hero Section -->
<section class="hero">
    <h1 class="hero-animate">عنوان</h1>
    <p class="hero-animate-delay-1">نص</p>
    <a href="#" class="btn btn-primary btn-animate hero-animate-delay-2">زر</a>
</section>

<!-- Products Grid -->
<div class="products-grid stagger-animate">
    <div class="product-card hover-lift">
        <img src="..." class="product-image-zoom">
    </div>
</div>
```

### صفحة المنتجات - Products Page

```html
<div class="products-grid stagger-animate">
    <div class="product-card hover-lift">
        <div class="product-image">
            <img src="..." class="product-image-zoom">
        </div>
    </div>
</div>
```

---

## الأداء - Performance

- ✅ استخدام `will-change` للأداء الأمثل
- ✅ تقليل الأنيميشن على الأجهزة المحمولة
- ✅ دعم `prefers-reduced-motion` لإمكانية الوصول
- ✅ استخدام `transform` و `opacity` فقط (GPU accelerated)

---

## التوافق - Compatibility

- ✅ Chrome/Edge (Latest)
- ✅ Firefox (Latest)
- ✅ Safari (Latest)
- ✅ Mobile Browsers

---

## ملاحظات - Notes

1. جميع الأنيميشن تستخدم `cubic-bezier` للحركة الناعمة
2. الأنيميشن محسّنة للأداء باستخدام GPU
3. تقليل الأنيميشن على الأجهزة المحمولة تلقائياً
4. دعم كامل لإمكانية الوصول

---

## أمثلة إضافية - Additional Examples

### بطاقة منتج كاملة - Complete Product Card

```html
<div class="product-card hover-lift animate-fade-in-up">
    <div class="product-image">
        <img src="product.jpg" class="product-image-zoom">
    </div>
    <div class="product-info">
        <h3>اسم المنتج</h3>
        <a href="#" class="btn btn-primary btn-animate">إضافة للسلة</a>
    </div>
</div>
```

### أيقونة متحركة - Animated Icon

```html
<i class="fas fa-shopping-cart icon-bounce"></i>
```

---

تم إنشاء هذا الملف بواسطة - Created by: AI Assistant
التاريخ - Date: 2024

