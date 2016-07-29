__author__ = 'zqi2'

import requests
from requests.auth import HTTPBasicAuth

from entity import Entity


class NodeData(Entity):
    def __init__(self):
        self._base_url = ''
        self._urls = {}
        self._config_section = ''

        Entity.__init__(self, config_section='node_data')

    def latest_by_project(self, projectId, auth=None):
        url = self._urls['latest_by_project'].replace("<projectId>", str(projectId))
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("LATEST_BY_PROJECT: %s", url)
        self._logger.info("%s %s", r.status_code, r.headers['content-type'])
        self._logger.info(r.text)
        return r

    def latest_by_project_and_label(self, projectId, label, auth=None):
        url = self._urls['latest_by_project_and_label'].replace("<projectId>", str(projectId))
        url = url.replace("<label>", str(label))
        headers = {'Accept': 'application/json'}
        r = requests.get(url, auth=auth, headers=headers)
        self._logger.info("LATEST_BY_PROJECT: %s", url)
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

    entity = NodeData()

    # LIST
    entity.list(auth)

    # VIEW
    entity.view(4)

    # SEARCH
    entity.search('label=temp', auth)

    # LATEST_BY_PROJECT
    entity.latest_by_project(projectId=1, auth=auth)

    # LATEST_BY_PROJECT_AND_LABEL
    label = "Temp"
    entity.latest_by_project_and_label(projectId=1, label=label, auth=auth)

    # CREATE
    data = {'nodeId': '1', 'label': 'temp', 'value': '33.33', 'remark':'anything max 40 char'}
    r = entity.create(data, auth)

    if r.status_code == 201:
        # UPDATE
        obj = r.json()
        obj['label'] = 'temp1'
        obj['value'] = '44.44'
        r2 = entity.update(obj, auth)

        # DELETE
        r3 = entity.delete(obj['id'], auth)
