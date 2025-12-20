# Custom Design System Implementation Guide

## Overview

This document details the implementation of our custom design system for the Gemini AI Learning Platform. The design system follows a philosophy of approachability, clarity, and achievement-oriented learning experiences.

## Design Philosophy

### Core Principles

1. **Approachability (親しみやすさ)**: Creating a welcoming atmosphere that doesn't intimidate beginners
2. **Clarity (明快さ)**: Organized information with clear navigation paths
3. **Achievement (達成感)**: Gamification elements that build on small successes
4. **Trustworthiness (信頼感)**: Professional quality that inspires confidence

### Target Audience

- "50歳のお母さんでも使える" (Even a 50-year-old mother can use it)
- Complete beginners to AI and prompt engineering
- Self-learners who want to avoid frustration

## Files Modified

### 1. New Files Created

#### `/public/assets/css/custom-style.css`
Complete design system CSS file (1000+ lines) including:
- CSS custom properties for all design tokens
- Typography system with Noto Sans JP
- Component library (buttons, cards, forms, alerts, badges)
- Layout system (containers, grids)
- Responsive design system
- Animation and transition utilities

### 2. HTML Files Updated

#### `/public/index.html`
**Before:**
```html
<link rel="stylesheet" href="assets/css/style.css">
<link rel="stylesheet" href="assets/css/landing.css">
```

**After:**
```html
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/custom-style.css">
```

**Content Changes:**
- Hero title: "プロンプトエンジニアリングで人生の可能性を広げよう"
- Section title: "初心者から、創れる人に"
- CTA: "1分後、プロンプトエンジニアリングの世界でお会いしましょう。"
- Button classes: Updated to `btn-primary`, `btn-white`, `btn-large`

### 3. PHP Files Updated

#### `/includes/header.php`
- Changed `.site-header` to `.header`
- Changed `.header-inner` to `.header__container`
- Changed `.logo` to `.header__logo`
- Changed `.main-nav` to `.header__nav`
- Added `.header__link` class for navigation links

#### `/public/login.php`
- Added Google Fonts link for Noto Sans JP
- Changed stylesheet from `style.css` to `custom-style.css`
- Auth page classes remain compatible

#### `/public/register.php`
- Added Google Fonts link
- Changed stylesheet to `custom-style.css`

#### `/public/dashboard.php`
- Updated stylesheet link
- All component classes remain compatible with our custom design system

#### Other PHP Files Updated:
- `/public/course.php`
- `/public/lesson.php`
- `/public/subscribe.php`
- `/public/subscription-success.php`
- `/public/forgot-password.php`
- `/public/reset-password.php`
- `/public/index.php`

## Design System Specifications

### Color Palette

#### Primary Colors
```css
--primary-navy: #1a2a3a;        /* Headers, footers, trust elements */
--primary-green: #2ecc71;       /* CTAs, progress, success */
--primary-white: #ffffff;       /* Backgrounds, cards */
```

#### Secondary Colors
```css
--secondary-light-gray: #f8f9fa; /* Section backgrounds */
--secondary-text-gray: #6c757d;  /* Supplementary text */
--secondary-dark-text: #2c3e50;  /* Body text */
```

#### Accent Colors (Programming Languages)
```css
--accent-html: #e44d26;
--accent-css: #264de4;
--accent-javascript: #f7df1e;
--accent-python: #3776ab;
--accent-ruby: #cc342d;
```

### Typography

#### Font Families
- **Primary Japanese**: Noto Sans JP (400, 500, 700)
- **Primary English**: Noto Sans
- **Code**: Source Code Pro, Consolas, Monaco

#### Font Sizes
```css
--text-hero: 2.5rem;      /* 40px: Hero sections */
--text-h1: 2rem;          /* 32px: Main headings */
--text-h2: 1.5rem;        /* 24px: Sub headings */
--text-h3: 1.25rem;       /* 20px: Card headings */
--text-body: 1rem;        /* 16px: Body text */
--text-small: 0.875rem;   /* 14px: Small text */
--text-caption: 0.75rem;  /* 12px: Captions */
```

#### Font Weights
- Bold: 700 (headings, buttons)
- Medium: 500 (navigation, labels)
- Regular: 400 (body text)

#### Line Heights
- Tight: 1.4 (headings)
- Normal: 1.6 (body text)
- Loose: 1.8 (long-form content)

### Spacing System

Based on 8px grid system:
```css
--space-1: 0.5rem;    /* 8px */
--space-2: 1rem;      /* 16px */
--space-3: 1.5rem;    /* 24px */
--space-4: 2rem;      /* 32px */
--space-6: 3rem;      /* 48px */
--space-8: 4rem;      /* 64px */
--space-12: 6rem;     /* 96px - section gaps */
```

### Components

#### Buttons

**Primary Button (Green CTA)**
```html
<button class="btn btn-primary">まずは無料ではじめる</button>
```
- Background: `#2ecc71` (emerald green)
- Hover: Lifts 2px, darker green
- Use for: Main CTAs, important actions

**Secondary Button (Navy Outline)**
```html
<button class="btn btn-secondary">詳しく見る</button>
```
- Border: 2px solid navy
- Hover: Fills with navy background

**White Button (For Dark Backgrounds)**
```html
<button class="btn btn-white">無料で始める</button>
```
- Use on: Green hero sections, dark footers

**Button Sizes:**
- `.btn-sm` / `.btn-small`: Compact buttons
- `.btn-lg` / `.btn-large`: Hero CTAs
- `.btn-block`: Full-width buttons

#### Cards

**Basic Card**
```html
<div class="card">
  <h3>Card Title</h3>
  <p>Card content...</p>
</div>
```
- Border radius: 12px
- Shadow: Subtle (0 2px 8px rgba(0,0,0,0.08))
- Hover: Lifts 4px with increased shadow

**Course/Language Card**
```html
<div class="language-card">
  <div class="language-card__icon">🎓</div>
  <div class="language-card__content">
    <h3 class="language-card__title">Course Title</h3>
    <p class="language-card__description">Description...</p>
  </div>
</div>
```

#### Forms

**Input Fields**
```html
<div class="form-group">
  <label for="email">メールアドレス</label>
  <input type="email" id="email" class="input">
</div>
```
- Border: 2px solid #e1e5e9
- Focus: Green border (#2ecc71) with subtle shadow
- Border radius: 8px

#### Alerts

```html
<div class="alert alert-success">登録が完了しました！</div>
<div class="alert alert-error">エラーが発生しました</div>
<div class="alert alert-info">お知らせ</div>
<div class="alert alert-warning">警告</div>
```
- Left border: 4px accent color
- Soft background colors
- Clear typography

#### Badges

```html
<span class="badge badge-free">無料</span>
<span class="badge badge-premium">プレミアム</span>
<span class="badge badge-info">初級</span>
```

#### Progress Bars

```html
<div class="progress-bar">
  <div class="progress-bar__fill" style="width: 65%"></div>
</div>
```
- Height: 8px
- Background: Light gray
- Fill: Green gradient
- Smooth animation on width change

### Layout System

#### Container
```html
<div class="container">
  <!-- Max-width: 1200px, centered -->
</div>
```

#### Card Grid
```html
<div class="card-grid">
  <!-- Responsive grid: auto-fit minmax(280px, 1fr) -->
  <div class="card">...</div>
  <div class="card">...</div>
</div>
```

#### Two Column Layout
```html
<div class="two-column">
  <div>Left column</div>
  <div>Right column</div>
</div>
```

### Responsive Breakpoints

```css
--breakpoint-sm: 640px;   /* Small devices */
--breakpoint-md: 768px;   /* Tablets */
--breakpoint-lg: 1024px;  /* Desktops */
--breakpoint-xl: 1280px;  /* Large screens */
```

**Mobile-First Approach:**
- Base styles for mobile
- Media queries for larger screens
- Touch-friendly tap targets (min 44x44px)
- Collapsible navigation on mobile
- Stacked layouts on small screens

### Animation & Transitions

#### Standard Transitions
```css
--transition-fast: 0.15s ease;
--transition-normal: 0.2s ease;
--transition-slow: 0.3s ease;
```

#### Hover Effects
- **Cards**: translateY(-4px) + shadow increase
- **Buttons**: translateY(-2px) + shadow
- **Links**: Color change to green

#### Fade In Up Animation
```html
<div class="fade-in-up">...</div>
<div class="fade-in-up stagger-1">...</div>
<div class="fade-in-up stagger-2">...</div>
```

### Accessibility

- **Focus States**: 2px green outline with 2px offset
- **Skip Links**: Hidden until focused
- **Color Contrast**: WCAG AA compliant (4.5:1)
- **Semantic HTML**: Proper heading hierarchy
- **Landmark Regions**: header, nav, main, footer
- **Alt Text**: Required for all images

## Before/After Comparison

### Landing Page Hero

**Before:**
- Generic blue gradient background
- Standard "Start Learning" CTA
- Generic feature descriptions

**After:**
- Subtle blue-to-white gradient (#f8fbff to white)
- Inspiring copy: "プロンプトエンジニアリングで人生の可能性を広げよう"
- Clear value proposition: "初心者でも独学で、挫折せずに学べる"
- Prominent green CTAs with white secondary buttons

### Navigation Header

**Before:**
- `.site-header` with `.header-inner`
- Generic styling

**After:**
- `.header` with BEM methodology (`.header__container`, `.header__logo`, `.header__nav`)
- Sticky positioning
- Clean white background with subtle shadow
- Green accent for logo and hover states

### Authentication Pages

**Before:**
- Purple gradient background
- Standard form styling

**After:**
- Soft gradient background (#f8fbff to #e8f5e9)
- Larger, more welcoming cards
- Green focus states on inputs
- Clear visual hierarchy

### Course Cards

**Before:**
- Basic card styling
- Standard progress bars

**After:**
- Hover lift effect (translateY(-4px))
- Smooth shadow transitions
- Green progress bars with gradient
- Clear badge system (free/premium)
- Icon integration for better visual appeal

## Copy & Messaging Changes

Following a friendly, encouraging tone:

| Element | Before | After |
|---------|--------|-------|
| Hero Title | "ChatGPTを実践的に学ぼう" | "プロンプトエンジニアリングで人生の可能性を広げよう" |
| Section Title | "このプラットフォームの特徴" | "初心者から、創れる人に" |
| CTA Section | "今すぐマスターしよう" | "1分後、プロンプトエンジニアリングの世界でお会いしましょう。" |
| Button Text | "無料で始める" | "まずは無料ではじめる" |
| Hero Subtitle | Standard description | "初心者でも独学で、挫折せずに学べる学習環境を提供します。" |

## Implementation Notes

### What Changed
1. **Complete visual redesign** following modern design principles
2. **Color palette** switched from blue/purple to navy/green
3. **Typography** changed to Noto Sans JP for better Japanese readability
4. **Spacing** standardized to 8px grid system
5. **Component library** rebuilt with BEM methodology
6. **Animations** added for better UX (hover effects, transitions)
7. **Copy** updated to match an encouraging, beginner-friendly tone

### What Stayed the Same
1. **Functionality**: All features work exactly as before
2. **Form behavior**: Login, registration, etc. unchanged
3. **Database structure**: No backend changes
4. **File structure**: Same organization
5. **JavaScript**: No JS changes needed

### Browser Compatibility
- Modern browsers (Chrome, Firefox, Safari, Edge)
- CSS Grid and Flexbox used throughout
- CSS Custom Properties (CSS Variables)
- Google Fonts loaded via CDN

### Performance Considerations
- Single CSS file (progate-style.css) - no multiple stylesheet loads
- Google Fonts preconnected for faster loading
- Optimized animations with `transform` and `opacity`
- No heavy frameworks (pure CSS)

## Testing Checklist

### Visual Testing
- [x] Landing page matches modern design aesthetic
- [x] Auth pages (login/register) styled correctly
- [x] Dashboard course cards display properly
- [x] Headers/footers consistent across pages
- [x] Form inputs have proper focus states
- [x] Buttons have hover effects

### Responsive Testing
- [ ] Mobile (320px - 767px): Single column layouts, hamburger menu
- [ ] Tablet (768px - 1023px): Two column where appropriate
- [ ] Desktop (1024px+): Full layout with all features

### Functionality Testing
- [x] All links work
- [x] Forms submit correctly
- [x] Auth flows unchanged
- [x] Course access logic intact
- [x] Progress tracking displays

### Browser Testing
- [ ] Chrome (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Edge (latest)
- [ ] Mobile Safari (iOS)
- [ ] Chrome Mobile (Android)

## Future Enhancements

### Phase 2 Improvements
1. **Character Integration**: Add mascot characters (like にんじゃわんこ)
2. **Micro-interactions**: Celebration animations on lesson completion
3. **Gamification**: Level badges, achievement popups
4. **Dark Mode**: Add dark theme option
5. **Advanced Animations**: Page transitions, loading states
6. **Custom Icons**: Replace Font Awesome with custom icon set

### Component Additions
- Dashboard widgets (stats, recent activity)
- Notification system
- Modal dialogs
- Tooltips
- Dropdown menus
- Tab navigation
- Accordion components

## Maintenance

### Adding New Colors
Add to `:root` in progate-style.css:
```css
:root {
  --new-color: #hex-code;
}
```

### Adding New Components
Follow BEM methodology:
```css
.component { /* Block */ }
.component__element { /* Element */ }
.component--modifier { /* Modifier */ }
```

### Updating Typography
Adjust font sizes in `:root` variables for consistent scaling:
```css
:root {
  --text-new-size: 1.125rem;
}
```

## Resources

### Design References
- Modern UI/UX best practices
- Accessibility guidelines (WCAG 2.1)
- Mobile-first responsive design

### Tools Used
- Google Fonts (Noto Sans JP)
- Font Awesome (icons)
- CSS Grid & Flexbox
- CSS Custom Properties

### Documentation
- [BEM Methodology](http://getbem.com/)
- [CSS Custom Properties](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)
- [Responsive Design](https://web.dev/responsive-web-design-basics/)

## Credits

Design system based on modern, approachable learning platform principles.
Implementation by: Gemini AI Learning Platform Development Team
Date: December 2025

---

**Note**: This is a living document. Update as the design system evolves.
