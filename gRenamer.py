import os
from gDriveFile import createService


class doRename:
    def __init__(self):
        self.root = f"."
        self.CLIENT_SECRET_FILE = self.root + '\\credentials.json'
        self.API_NAME = 'drive'
        self.API_VERSION = 'v3'
        self.fileToCopyId = '13UVwqTY0LDygku8-fA0L5Qxt-gyhRihE'
        self.copyFolderId = '1cSRwzCcLUgCZm4b0yBLyyi6HxWnzdQL4'
        self.renamerCsvFile = self.root + '\\rename.csv'
        self.finalLinks = self.root + '\\finalLinks.csv'
        self.allLinkData = []
        self.testOn = False
        self.readRenamerFile()
        if len(self.allLinkData) > 0:
            if self.testOn:
                self.__testing__()
            else:
                self.SCOPES = ['https://www.googleapis.com/auth/drive.metadata.readonly',
                               'https://www.googleapis.com/auth/drive']
                self.service = createService(
                    self.CLIENT_SECRET_FILE, self.API_NAME, self.API_VERSION, self.SCOPES)
                self.doStuff()
        else:
            print('rename.csv is empty')
            return None

    def __testing__(self):
        print('This is Testing not for production............')
        for q in self.allLinkData:
            k = q.strip().split(",")
            a = k[0]
            b = k[1]
            c = k[2]
            d = k[3]
            with open(self.finalLinks, "a") as file:
                print('file writing...', a, d)
                file.write(f"{a}, {d}\n")

    def doStuff(self):
        for linkData in self.allLinkData:
            temp_data = linkData.strip().split(",")
            try:
                fileLink_ = temp_data[0]
                fileName_ = temp_data[1]
                fileFolderId_ = temp_data[2]
                child_id = temp_data[3]
                gFinalLink = temp_data[4]
                file_to_copy_id = fileLink_
                copy_file_name = fileName_
                copy_folder_id = fileFolderId_
                try:
                    self.copied_file = {'title': 'COPYEDFILE',
                                        'name': copy_file_name, 'parents': [copy_folder_id]}
                    copy_file_to_drive_status, temp_id = self.copyFileToDrive(
                        file_to_copy_id=file_to_copy_id, folder_id=copy_folder_id, copied_file_body=self.copied_file)
                except Exception as e:
                    self.__ErrFire__(
                        module='gRenamer', class_='doRename', function='doStuff', err=e)
                    with open(self.finalLinks, 'a') as file:
                        file.write(f"{gFinalLink},{child_id}")
                if copy_file_to_drive_status:
                    get_Publink_status, temp_link = self.getPublicLink(
                        temp_id)
                    if get_Publink_status:
                        try:
                            with open(self.finalLinks, "a") as file:
                                file.write(f"{temp_link}, {child_id}\n")
                        except Exception as e:
                            self.__ErrFire__(
                                module='gRenamer', function='doStuff (copy_file_to_drive_status) ' + copy_file_to_drive_status, class_='doRenamer', err=e)
                    else:
                        with open(self.finalLinks, 'a') as file:
                            file.write(f"{gFinalLink},{child_id}")
                else:
                    with open(self.finalLinks, 'a') as file:
                        file.write(f"{gFinalLink},{child_id}")
            except Exception as e:
                self.__ErrFire__(module='gRenamer',
                                 class_='doRename', function='doStuff', err=e)

    def __del__(self):
        print('Cleaning Renamer Objects (:')

    def __ErrFire__(self, module='', class_='', function='', err='', line=''):
        try:
            y = '['
            if module != '':
                y += module
            if class_ != '':
                y += '.'+class_
            y += ' => '
            if function != '':
                y += function
            if line != '':
                y += ': ' + line
            y += '] '
            if err != '':
                print(y, err)
                return y, err
            print(y)
            return y
        except Exception as e:
            print('[gRenamer.py -> __ErrFire__] creating error: ', e)

    def readRenamerFile(self):
        try:
            with open(self.renamerCsvFile, 'r') as file:
                self.allLinkData = file.readlines()
        except Exception as e:
            self.__ErrFire__(module='gRenamer', function='readRenamerFile',
                             class_='doRename', err=e)

    def copyFileToDrive(self, file_to_copy_id='', folder_id='', copied_file_body=''):
        try:
            copied_file_obj = self.service.files().copy(
                fileId=file_to_copy_id, body=self.copied_file).execute()
            copied_file_file_id = copied_file_obj['id']
            return True, copied_file_file_id
        except Exception as e:
            self.__ErrFire__(
                module='gRenamer', function='copyFileToDrive', class_='doRenamer', err=e)

    def getPublicLink(self, file_id=''):
        request_body = {
            'role': 'reader',
            'type': 'anyone'
        }
        response_permission = self.service.permissions().create(
            fileId=file_id,
            body=request_body
        ).execute()
        response_share_link = self.service.files().get(
            fileId=file_id,
            fields='webViewLink'
        ).execute()
        return True, response_share_link['webViewLink']
