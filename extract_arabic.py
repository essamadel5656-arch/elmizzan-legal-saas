import os
import re

directory = 'resources/views/livewire'

files_to_check = [
    'appointments/appointment-create.blade.php',
    'appointments/appointment-edit.blade.php',
    'lawyers/lawyer-create.blade.php',
    'lawyers/lawyer-edit.blade.php',
    'clients/client-create.blade.php',
    'clients/client-edit.blade.php',
    'courts/court-create.blade.php',
    'courts/court-edit.blade.php',
    'payments/client-payment-create.blade.php',
    'jurisdictions/jurisdiction-create.blade.php',
    'jurisdictions/jurisdiction-edit.blade.php'
]

matches = set()

for file in files_to_check:
    path = os.path.join(directory, file)
    if os.path.exists(path):
        with open(path, 'r', encoding='utf-8') as f:
            content = f.read()
            # Extract from >...<
            tags = re.findall(r'>([^<]*[\u0600-\u06FF]+[^<]*)<', content)
            for t in tags:
                matches.add(t.strip())
            
            # Extract from placeholder="..."
            placeholders = re.findall(r'placeholder="([^"]*[\u0600-\u06FF]+[^"]*)"', content)
            for p in placeholders:
                matches.add(p.strip())
                
            # Extract from value="..."
            values = re.findall(r'value="([^"]*[\u0600-\u06FF]+[^"]*)"', content)
            for v in values:
                matches.add(v.strip())

with open('arabic_strings.txt', 'w', encoding='utf-8') as f:
    for m in matches:
        if m:
            f.write(m + '\n')
