<tr class="tree-node">
    <td>
        <div style="display: flex; align-items: center; gap: 8px; padding-inline-start: {{ $depth * 20 }}px">
            @if($node['children'])
                <button type="button" class="toggle-button" data-target="#children-{{ $treeId }}"
                        aria-label="@lang('messages.toggle_node')" aria-expanded="true">-</button>
            @else
                <span style="display: inline-block; width: 27px; flex: 0 0 27px"></span>
            @endif
            <span class="tree-name">{{ $isUrdu ? ($node['name_ur'] ?: $node['name_en']) : $node['name_en'] }}</span>
        </div>
    </td>
    <td class="tree-balance">{{ number_format($node['balance'], 2) }}</td>
</tr>
@if($node['children'])
    <tr id="children-{{ $treeId }}" class="tree-children-row">
        <td colspan="2">
            <table class="tree-table">
                <tbody>
                    @foreach($node['children'] as $childIndex => $child)
                        @include('reports.balance-sheet.tree-node', [
                            'node' => $child,
                            'treeId' => $treeId . '-' . $childIndex,
                            'depth' => $depth + 1,
                            'isUrdu' => $isUrdu,
                        ])
                    @endforeach
                </tbody>
            </table>
        </td>
    </tr>
@endif
