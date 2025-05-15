import json

class LoadJson:
    def __init__(self, path):
        self.path = path

    def load(self):
        # Read the file
        with open(self.path, 'r') as file:
            return json.load(file)

    def update(self, data):
        # Write the file
        with open(self.path, 'w') as file:
            json.dump(data, file, indent=4)


def mac_in_wl(mac):
    data = LoadJson('data/json/white_list.json').load()
    for category in data['white-list']:
        for entry in category['wl']:
            if entry['mac'].lower() == mac.lower():
                return [category['name'], entry['name']]
    return [None, None]

def save_json_data(data,path):
    with open(path, 'w') as file:
        json.dump(data, file, indent=4)