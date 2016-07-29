__author__ = 'zqi2'

import requests
from requests.auth import HTTPBasicAuth

from entity import Entity


class NodeSummary(Entity):
    def __init__(self):
        self._base_url = ''
        self._urls = {}
        self._config_section = ''
        Entity.__init__(self, config_section='node_summary')

    def node_crowd_average(self, auth=None):
        url = self._urls['node_crowd_average']

        headers = {'Content-Type': 'application/json', 'Accept': 'application/json'}
        r = requests.post(url, auth=auth, headers=headers)
        self._logger.info("node_crowd_average: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r


if __name__ == '__main__':

    # # Read options from command line
    # argParser = argparse.ArgumentParser('Public Camera: API Entity')
    # argParser.add_argument('-c', '--configFile', help="Configuration file", required=False)
    # argParser.add_argument('-s', '--configSession', help="Configuration session", required=False)
    # argParser.add_argument('-u', '--username', help="Username", required=False)
    # argParser.add_argument('-p', '--password', help="Password", required=False)
    # args = argParser.parse_args()

    # Username and Password for Authentication
    username = 'user1'
    password = '123456'
    auth = HTTPBasicAuth(username, password)

    entity = NodeSummary()

    # LIST
    entity.list(auth)

    # VIEW
    entity.view(109)

    # SEARCH
    # entity.search('label=crowd_15min', auth)
    entity.search('type=0', auth)

    # CREATE
    data = {'node_id': 1, 'type': 0, 'value': '20'}
    r = entity.create(data, auth)

    if r.status_code == 201:
        # UPDATE
        obj = r.json()
        obj['value'] = '10'
        r2 = entity.update(obj, auth)

        # DELETE
        r3 = entity.delete(obj['id'], auth)

    entity.node_crowd_average()
