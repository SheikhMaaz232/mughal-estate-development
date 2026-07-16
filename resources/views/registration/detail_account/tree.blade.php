@extends('layouts.backend')

@section('content')
<div class="container mt-4">
    <h1>{{ __('messages.accounts_tree') }}</h1>
    <p class="text-muted">{{ __('messages.english') }} / {{ __('messages.urdu') }}</p>

    <style>
        .tree {
            padding: 20px;
            font-size: 14px;
        }
        .tree ul {
            list-style-type: none;
            padding-left: 20px;
            margin: 0;
        }
        .tree li {
            margin: 6px 0;
            line-height: 1.6;
        }
        .node-wrapper {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
        }
        .toggle-btn {
            cursor: pointer;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #fff;
            user-select: none;
            border: none;
            border-radius: 3px;
            font-size: 14px;
            background-color: #007bff;
            transition: background-color 0.2s;
            flex-shrink: 0;
            padding: 0;
        }
        .toggle-btn:hover {
            background-color: #0056b3;
        }
        .toggle-btn:active {
            transform: scale(0.95);
        }
        .children-list {
            display: block;
            animation: slideDown 0.2s ease-out;
        }
        .children-list.collapsed {
            display: none;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .node-label {
            padding: 4px 8px;
            border-radius: 3px;
            transition: background-color 0.2s;
        }
        .node-label:hover {
            background-color: #f0f0f0;
        }
        .urdu {
            font-family: 'Noto Nastaliq Urdu', serif;
            font-size: 16px;
        }
        .no-children {
            width: 24px;
            display: inline-block;
        }
    </style>

    <div class="tree">
        <ul>
            @foreach($accountsTree as $mh)
                <li>
                    <div class="node-wrapper">
                        @if(!empty($mh['control_heads']))
                            <button class="toggle-btn" onclick="toggleChildren(event)">+</button>
                        @else
                            <span class="no-children"></span>
                        @endif
                        <span class="node-label">
                            <strong>
                                @if(app()->getLocale() == 'ur')
                                    <span class="urdu">{{ $mh['name_ur'] ?? '' }}</span>
                                @else
                                    {{ $mh['name_en'] ?? '' }}
                                @endif
                            </strong>
                        </span>
                    </div>

                    @if(!empty($mh['control_heads']))
                        <ul class="children-list">
                            @foreach($mh['control_heads'] as $ch)
                                <li>
                                    <div class="node-wrapper">
                                        @if(!empty($ch['sub_heads']))
                                            <button class="toggle-btn" onclick="toggleChildren(event)">+</button>
                                        @else
                                            <span class="no-children"></span>
                                        @endif
                                        <span class="node-label">
                                            @if(app()->getLocale() == 'ur')
                                                <span class="urdu">{{ $ch['name_ur'] ?? '' }}</span>
                                            @else
                                                {{ $ch['name_en'] ?? '' }}
                                            @endif
                                        </span>
                                    </div>

                                    @if(!empty($ch['sub_heads']))
                                        <ul class="children-list">
                                            @foreach($ch['sub_heads'] as $sh)
                                                <li>
                                                    <div class="node-wrapper">
                                                        @if(!empty($sh['sub_sub_heads']))
                                                            <button class="toggle-btn" onclick="toggleChildren(event)">+</button>
                                                        @else
                                                            <span class="no-children"></span>
                                                        @endif
                                                        <span class="node-label">
                                                            @if(app()->getLocale() == 'ur')
                                                                <span class="urdu">{{ $sh['name_ur'] ?? '' }}</span>
                                                            @else
                                                                {{ $sh['name_en'] ?? '' }}
                                                            @endif
                                                        </span>
                                                    </div>

                                                    @if(!empty($sh['sub_sub_heads']))
                                                        <ul class="children-list">
                                                            @foreach($sh['sub_sub_heads'] as $ssh)
                                                                <li>
                                                                    @if($ssh['name_en'] || $ssh['name_ur'])
                                                                        <div class="node-wrapper">
                                                                            @if(!empty($ssh['sub_sub_sub_heads']))
                                                                                <button class="toggle-btn" onclick="toggleChildren(event)">+</button>
                                                                            @else
                                                                                <span class="no-children"></span>
                                                                            @endif
                                                                            <span class="node-label">
                                                                                @if(app()->getLocale() == 'ur')
                                                                                    <span class="urdu">{{ $ssh['name_ur'] ?? '' }}</span>
                                                                                @else
                                                                                    {{ $ssh['name_en'] ?? '' }}
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    @endif

                                                                    @if(!empty($ssh['sub_sub_sub_heads']))
                                                                        <ul class="children-list">
                                                                            @foreach($ssh['sub_sub_sub_heads'] as $sssh)
                                                                                <li>
                                                                                    @if($sssh['name_en'] || $sssh['name_ur'])
                                                                                        <div class="node-wrapper">
                                                                                            @if(!empty($sssh['detail_accounts']))
                                                                                                <button class="toggle-btn" onclick="toggleChildren(event)">+</button>
                                                                                            @else
                                                                                                <span class="no-children"></span>
                                                                                            @endif
                                                                                            <span class="node-label">
                                                                                                @if(app()->getLocale() == 'ur')
                                                                                                    <span class="urdu">{{ $sssh['name_ur'] ?? '' }}</span>
                                                                                                @else
                                                                                                    {{ $sssh['name_en'] ?? '' }}
                                                                                                @endif
                                                                                            </span>
                                                                                        </div>
                                                                                    @endif

                                                                                    @if(!empty($sssh['detail_accounts']))
                                                                                        <ul class="children-list">
                                                                                            @foreach($sssh['detail_accounts'] as $da)
                                                                                                <li>
                                                                                                    <div class="node-wrapper">
                                                                                                        <span class="no-children"></span>
                                                                                                        <span class="node-label">
                                                                                                            @if(app()->getLocale() == 'ur')
                                                                                                                <span class="urdu">{{ $da['name_ur'] }}</span>
                                                                                                            @else
                                                                                                                {{ $da['name_en'] }}
                                                                                                            @endif
                                                                                                        </span>
                                                                                                    </div>
                                                                                                </li>
                                                                                            @endforeach
                                                                                        </ul>
                                                                                    @endif
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif

                                                                    @if(!empty($ssh['detail_accounts']))
                                                                        <ul class="children-list">
                                                                            @foreach($ssh['detail_accounts'] as $da_list)
                                                                                @if(!empty($da_list['detail_accounts']))
                                                                                    @foreach($da_list['detail_accounts'] as $da)
                                                                                        <li>
                                                                                            <div class="node-wrapper">
                                                                                                <span class="no-children"></span>
                                                                                                <span class="node-label">
                                                                                                    @if(app()->getLocale() == 'ur')
                                                                                                        <span class="urdu">{{ $da['name_ur'] }}</span>
                                                                                                    @else
                                                                                                        {{ $da['name_en'] }}
                                                                                                    @endif
                                                                                                </span>
                                                                                            </div>
                                                                                        </li>
                                                                                    @endforeach
                                                                                @else
                                                                                    @if(!empty($da_list['name_en']))
                                                                                        <li>
                                                                                            <div class="node-wrapper">
                                                                                                <span class="no-children"></span>
                                                                                                <span class="node-label">
                                                                                                    @if(app()->getLocale() == 'ur')
                                                                                                        <span class="urdu">{{ $da_list['name_ur'] }}</span>
                                                                                                    @else
                                                                                                        {{ $da_list['name_en'] }}
                                                                                                    @endif
                                                                                                </span>
                                                                                            </div>
                                                                                        </li>
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        </ul>
                                                                    @endif
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    function toggleChildren(event) {
        event.preventDefault();
        const button = event.target;
        const parentLi = button.closest('li');
        const childrenList = parentLi.querySelector('.children-list');

        if (childrenList) {
            childrenList.classList.toggle('collapsed');
            // Change button text between + and −
            button.textContent = childrenList.classList.contains('collapsed') ? '+' : '−';
        }
    }

    // Initialize all children lists as collapsed on page load
    document.addEventListener('DOMContentLoaded', function() {
        const allChildrenLists = document.querySelectorAll('.children-list');
        allChildrenLists.forEach(list => {
            list.classList.add('collapsed');
        });

        // Update all toggle buttons to show +
        const allButtons = document.querySelectorAll('.toggle-btn');
        allButtons.forEach(btn => {
            btn.textContent = '+';
        });
    });
</script>
@endsection
