@props(['country' => 'EG', 'size' => '1.4rem', 'class' => ''])
@php
    $c = strtoupper(trim((string)$country));
@endphp
<span class="flag-icon-container {{ $class }}"
      style="width: {{ $size }}; height: calc({{ $size }} * 0.67); min-width: {{ $size }}; display: inline-flex; align-items: center; justify-content: center; vertical-align: middle; border-radius: 3px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.18); border: 1px solid rgba(0,0,0,0.08); flex-shrink: 0; line-height: 1;"
      title="{{ $c }}">
    @if($c === 'EG')
        <svg viewBox="0 0 900 600" width="100%" height="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="900" height="200" fill="#C8102E"/>
            <rect y="200" width="900" height="200" fill="#FFFFFF"/>
            <rect y="400" width="900" height="200" fill="#000000"/>
            <!-- Eagle of Saladin -->
            <g transform="translate(450, 300) scale(0.65)">
                <path d="M-60,-50 C-80,-10 -70,50 -40,90 C-20,110 -10,120 0,125 C10,120 20,110 40,90 C70,50 80,-10 60,-50 C45,-25 25,-10 0,-10 C-25,-10 -45,-25 -60,-50 Z" fill="#C69214"/>
                <path d="M0,-85 C15,-85 22,-70 20,-55 C12,-55 5,-60 0,-55 C-5,-60 -12,-55 -20,-55 C-22,-70 -15,-85 0,-85 Z" fill="#C69214"/>
                <path d="M12,-72 L26,-68 L15,-62 Z" fill="#C69214"/>
                <path d="M-22,-30 L22,-30 L18,40 L0,55 L-18,40 Z" fill="#FFFFFF" stroke="#C69214" stroke-width="4"/>
                <path d="M-12,-30 L-12,35 L0,45 L0,-30 Z" fill="#C8102E"/>
                <path d="M0,-30 L0,45 L12,35 L12,-30 Z" fill="#000000"/>
                <rect x="-40" y="95" width="80" height="18" rx="4" fill="#C69214"/>
            </g>
        </svg>
    @elseif($c === 'SA')
        <svg viewBox="0 0 900 600" width="100%" height="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="900" height="600" fill="#006C35"/>
            <!-- Arabic Shahada Representation & Sword -->
            <g fill="#FFFFFF">
                <path d="M250,220 Q280,180 320,210 T380,195 T450,215 T520,190 T590,215 T650,190 L650,240 Q600,220 550,240 T450,225 T350,245 T250,225 Z" opacity="0.95"/>
                <path d="M290,160 L310,160 L310,230 L290,230 Z M350,150 L370,150 L370,235 L350,235 Z M420,160 L440,160 L440,230 L420,230 Z M480,150 L500,150 L500,235 L480,235 Z M540,160 L560,160 L560,230 L540,230 Z M600,155 L620,155 L620,235 L600,235 Z"/>
                <path d="M640,310 L300,310 L280,315 L240,305 L280,295 L300,300 L640,300 L650,285 L660,285 L658,300 L675,300 L675,310 L658,310 L660,325 L650,325 Z"/>
                <circle cx="682" cy="305" r="7"/>
            </g>
        </svg>
    @elseif($c === 'AE')
        <svg viewBox="0 0 900 600" width="100%" height="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="225" y="0" width="675" height="200" fill="#00732F"/>
            <rect x="225" y="200" width="675" height="200" fill="#FFFFFF"/>
            <rect x="225" y="400" width="675" height="200" fill="#000000"/>
            <rect x="0" y="0" width="225" height="600" fill="#CE1126"/>
        </svg>
    @elseif($c === 'OM')
        <svg viewBox="0 0 900 600" width="100%" height="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="225" y="0" width="675" height="200" fill="#FFFFFF"/>
            <rect x="225" y="200" width="675" height="200" fill="#DB161B"/>
            <rect x="225" y="400" width="675" height="200" fill="#008000"/>
            <rect x="0" y="0" width="225" height="600" fill="#DB161B"/>
            <g transform="translate(112, 100) scale(0.65)" fill="#FFFFFF">
                <path d="M-60,-50 L60,60 M-60,60 L60,-50" stroke="#FFFFFF" stroke-width="12" stroke-linecap="round"/>
                <path d="M-15,-60 L15,-60 L12,-20 L25,20 L15,45 L0,55 L-15,45 L-25,20 L-12,-20 Z"/>
                <circle cx="0" cy="-68" r="10"/>
                <rect x="-28" y="15" width="56" height="12" rx="4"/>
            </g>
        </svg>
    @elseif($c === 'US')
        <svg viewBox="0 0 900 600" width="100%" height="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <!-- 13 stripes: alternating red (#B22234) and white -->
            @for($s = 0; $s < 13; $s++)
                <rect y="{{ $s * 46.15 }}" width="900" height="46.15" fill="{{ $s % 2 === 0 ? '#B22234' : '#FFFFFF' }}"/>
            @endfor
            <!-- Canton (blue field) -->
            <rect width="360" height="323" fill="#3C3B6E"/>
            <!-- 50 stars: simplified static layout -->
            <path fill="#FFFFFF" d="M30,23 L33,30 L40,30 L35,35 L37,42 L30,37 L23,42 L25,35 L20,30 L27,30 Z M90,23 L93,30 L100,30 L95,35 L97,42 L90,37 L83,42 L85,35 L80,30 L87,30 Z M150,23 L153,30 L160,30 L155,35 L157,42 L150,37 L143,42 L145,35 L140,30 L147,30 Z M210,23 L213,30 L220,30 L215,35 L217,42 L210,37 L203,42 L205,35 L200,30 L207,30 Z M270,23 L273,30 L280,30 L275,35 L277,42 L270,37 L263,42 L265,35 L260,30 L267,30 Z M330,23 L333,30 L340,30 L335,35 L337,42 L330,37 L323,42 L325,35 L320,30 L327,30 Z
                M60,58 L63,65 L70,65 L65,70 L67,77 L60,72 L53,77 L55,70 L50,65 L57,65 Z M120,58 L123,65 L130,65 L125,70 L127,77 L120,72 L113,77 L115,70 L110,65 L117,65 Z M180,58 L183,65 L190,65 L185,70 L187,77 L180,72 L173,77 L175,70 L170,65 L177,65 Z M240,58 L243,65 L250,65 L245,70 L247,77 L240,72 L233,77 L235,70 L230,65 L237,65 Z M300,58 L303,65 L310,65 L305,70 L307,77 L300,72 L293,77 L295,70 L290,65 L297,65 Z
                M30,93 L33,100 L40,100 L35,105 L37,112 L30,107 L23,112 L25,105 L20,100 L27,100 Z M90,93 L93,100 L100,100 L95,105 L97,112 L90,107 L83,112 L85,105 L80,100 L87,100 Z M150,93 L153,100 L160,100 L155,105 L157,112 L150,107 L143,112 L145,105 L140,100 L147,100 Z M210,93 L213,100 L220,100 L215,105 L217,112 L210,107 L203,112 L205,105 L200,100 L207,100 Z M270,93 L273,100 L280,100 L275,105 L277,112 L270,107 L263,112 L265,105 L260,100 L267,100 Z M330,93 L333,100 L340,100 L335,105 L337,112 L330,107 L323,112 L325,105 L320,100 L327,100 Z
                M60,128 L63,135 L70,135 L65,140 L67,147 L60,142 L53,147 L55,140 L50,135 L57,135 Z M120,128 L123,135 L130,135 L125,140 L127,147 L120,142 L113,147 L115,140 L110,135 L117,135 Z M180,128 L183,135 L190,135 L185,140 L187,147 L180,142 L173,147 L175,140 L170,135 L177,135 Z M240,128 L243,135 L250,135 L245,140 L247,147 L240,142 L233,147 L235,140 L230,135 L237,135 Z M300,128 L303,135 L310,135 L305,140 L307,147 L300,142 L293,147 L295,140 L290,135 L297,135 Z
                M30,163 L33,170 L40,170 L35,175 L37,182 L30,177 L23,182 L25,175 L20,170 L27,170 Z M90,163 L93,170 L100,170 L95,175 L97,182 L90,177 L83,182 L85,175 L80,170 L87,170 Z M150,163 L153,170 L160,170 L155,175 L157,182 L150,177 L143,182 L145,175 L140,170 L147,170 Z M210,163 L213,170 L220,170 L215,175 L217,182 L210,177 L203,182 L205,175 L200,170 L207,170 Z M270,163 L273,170 L280,170 L275,175 L277,182 L270,177 L263,182 L265,175 L260,170 L267,170 Z M330,163 L333,170 L340,170 L335,175 L337,182 L330,177 L323,182 L325,175 L320,170 L327,170 Z
                M60,198 L63,205 L70,205 L65,210 L67,217 L60,212 L53,217 L55,210 L50,205 L57,205 Z M120,198 L123,205 L130,205 L125,210 L127,217 L120,212 L113,217 L115,210 L110,205 L117,205 Z M180,198 L183,205 L190,205 L185,210 L187,217 L180,212 L173,217 L175,210 L170,205 L177,205 Z M240,198 L243,205 L250,205 L245,210 L247,217 L240,212 L233,217 L235,210 L230,205 L237,205 Z M300,198 L303,205 L310,205 L305,210 L307,217 L300,212 L293,217 L295,210 L290,205 L297,205 Z
                M30,233 L33,240 L40,240 L35,245 L37,252 L30,247 L23,252 L25,245 L20,240 L27,240 Z M90,233 L93,240 L100,240 L95,245 L97,252 L90,247 L83,252 L85,245 L80,240 L87,240 Z M150,233 L153,240 L160,240 L155,245 L157,252 L150,247 L143,252 L145,245 L140,240 L147,240 Z M210,233 L213,240 L220,240 L215,245 L217,252 L210,247 L203,252 L205,245 L200,240 L207,240 Z M270,233 L273,240 L280,240 L275,245 L277,252 L270,247 L263,252 L265,245 L260,240 L267,240 Z M330,233 L333,240 L340,240 L335,245 L337,252 L330,247 L323,252 L325,245 L320,240 L327,240 Z
                M60,268 L63,275 L70,275 L65,280 L67,287 L60,282 L53,287 L55,280 L50,275 L57,275 Z M120,268 L123,275 L130,275 L125,280 L127,287 L120,282 L113,287 L115,280 L110,275 L117,275 Z M180,268 L183,275 L190,275 L185,280 L187,287 L180,282 L173,287 L175,280 L170,275 L177,275 Z M240,268 L243,275 L250,275 L245,280 L247,287 L240,282 L233,287 L235,280 L230,275 L237,275 Z M300,268 L303,275 L310,275 L305,280 L307,287 L300,282 L293,287 L295,280 L290,275 L297,275 Z
                M30,303 L33,310 L40,310 L35,315 L37,322 L30,317 L23,322 L25,315 L20,310 L27,310 Z M90,303 L93,310 L100,310 L95,315 L97,322 L90,317 L83,322 L85,315 L80,310 L87,310 Z M150,303 L153,310 L160,310 L155,315 L157,322 L150,317 L143,322 L145,315 L140,310 L147,310 Z M210,303 L213,310 L220,310 L215,315 L217,322 L210,317 L203,322 L205,315 L200,310 L207,310 Z M270,303 L273,310 L280,310 L275,315 L277,322 L270,317 L263,322 L265,315 L260,310 L267,310 Z M330,303 L333,310 L340,310 L335,315 L337,322 L330,317 L323,322 L325,315 L320,310 L327,310 Z"/>
        </svg>
    @elseif($c === 'GB')
        <svg viewBox="0 0 900 600" width="100%" height="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="900" height="600" fill="#012169"/>
            <line x1="0" y1="0" x2="900" y2="600" stroke="#FFFFFF" stroke-width="120"/>
            <line x1="900" y1="0" x2="0" y2="600" stroke="#FFFFFF" stroke-width="120"/>
            <polygon fill="#C8102E" points="0,0 100,0 900,520 900,600 800,600 0,80"/>
            <polygon fill="#C8102E" points="900,0 800,0 0,520 0,600 100,600 900,80"/>
            <rect x="340" y="0" width="220" height="600" fill="#FFFFFF"/>
            <rect x="0" y="190" width="900" height="220" fill="#FFFFFF"/>
            <rect x="380" y="0" width="140" height="600" fill="#C8102E"/>
            <rect x="0" y="230" width="900" height="140" fill="#C8102E"/>
        </svg>
    @elseif($c === 'ES')
        <svg viewBox="0 0 900 600" width="100%" height="100%" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <rect width="900" height="150" fill="#AA151B"/>
            <rect y="150" width="900" height="300" fill="#F1BF00"/>
            <rect y="450" width="900" height="150" fill="#AA151B"/>
            <g transform="translate(260, 220)">
                <rect x="0" y="0" width="90" height="110" rx="4" fill="#FFFFFF" stroke="#AA151B" stroke-width="3"/>
                <rect x="3" y="3" width="41" height="52" fill="#AA151B"/>
                <rect x="49" y="58" width="38" height="49" fill="#AA151B"/>
                <rect x="49" y="3" width="38" height="52" fill="#F1BF00"/>
                <rect x="3" y="58" width="41" height="49" fill="#F1BF00"/>
                <ellipse cx="45" cy="118" rx="20" ry="12" fill="#AA151B"/>
                <rect x="-25" y="10" width="14" height="90" fill="#F1BF00" stroke="#AA151B" stroke-width="1"/>
                <rect x="101" y="10" width="14" height="90" fill="#F1BF00" stroke="#AA151B" stroke-width="1"/>
                <rect x="-28" y="40" width="20" height="4" fill="#AA151B" opacity="0.6"/>
                <rect x="98" y="40" width="20" height="4" fill="#AA151B" opacity="0.6"/>
            </g>
        </svg>
    @else
        <span class="fi fi-{{ strtolower($c) }}" style="font-size: {{ $size }}; width: 100%; height: 100%; display: block; line-height: 1;"></span>
    @endif
</span>
