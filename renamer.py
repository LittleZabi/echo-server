from server import saveChildNames
import os
from gRenamer import doRename
from vars import __ErrFire__


class Renamer:
    def __init__(self, childs) -> None:
        self.childList = childs
        self.root = '.'
        self.dataFile = self.root+'\\rename.csv'
        self.folderID = '1P8ZR0bsQjSy_978FNKfPeKAl_er4wp0Q'
        self.renamerList = []
        self.finalLinks = self.root + '\\finalLinks.csv'
        i = self.__write__()
        if i:
            self.__start__()
        else:
            print('[RENAMER] Error on Writing file')

    def __start__(self):
        print('starting...')
        t = doRename()
        q = saveChildNames()
        if q == 'success':
            del t
            self.__flush__()
            print('Saved successfully!')

    def __flush__(self):
        try:
            with open(self.dataFile, 'w') as file:
                file.write('')
            with open(self.finalLinks, 'w') as file:
                file.write('')
        except Exception as e:
            __ErrFire__(module='renamer', function='__flush__',
                        class_='renamer', err=e)

    def __write__(self):
        items = self.childList
        if len(items) > 0:
            try:
                with open(self.dataFile, 'w', encoding='utf-8') as file:
                    for child in items:
                        try:
                            final = child['finalLink']
                            name = child['new_filename']
                            child_id = child['child_id']
                            final = self.__driveToken__(final)
                            if final != False:
                                file.write(
                                    f"{final},{name},{self.folderID},{child_id}\n")
                            else:
                                if 'mega' in child['finalLink']:
                                    print(f"Parent link is Mega...")
                                else:
                                    print(f"Parent is not gDrive link...")
                                with open(self.finalLinks, "a") as file:
                                    file.write(
                                        f"{child['finalLink']}, {child_id}\n")
                        except Exception as e:
                            __ErrFire__(module='renamer', function='__write__ for child in items',
                                        class_='renamer', err=e)

                    return True
            except Exception as e:
                __ErrFire__(module='renamer', function='__write__',
                            class_='renamer', err=e)

        return False

    def __driveToken__(self, link):
        if 'drive.google' in link:
            k = link.split('/file/d/')[1]
            k = k.split('/view')[0]
            return k
        else:
            return False


# if __name__ == '__main__':
#     k = Renamer({})
    # link = 'https://drive.google.com/file/d/1-23432l2lk3j4lk234/view?ups=sharing'
